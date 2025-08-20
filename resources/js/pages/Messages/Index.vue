<script setup lang="ts">
import { ref, nextTick, onMounted, watch, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import UserLayout from "@/layouts/User/UserLayout.vue";

interface Message {
    id: number;
    content: string;
    sender_id: number;
    sender_name?: string;
    receiver_id: number;
    created_at: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    unread?: number;
}

// Props
const props = defineProps<{
    usersWhoMessaged: User[];
    receiver: User | null;
    messages: Message[];
}>();

const page = usePage();
const receiver = ref<User | null>(props.receiver);
const allMessages = ref<Message[]>([...props.messages]);
const displayedMessages = ref<Message[]>([]);
const usersWhoMessaged = ref<User[]>(
    props.usersWhoMessaged.map(u => ({
        ...u,
        unread:
            (page.props.auth.unreadByUser?.find((m: any) => m.sender_id === u.id)
                ?.total as number) || 0,
    }))
);

// Pagination
const messagesPerPage = 10;
function initMessages() {
    const total = allMessages.value.length;
    const start = total > messagesPerPage ? total - messagesPerPage : 0;
    displayedMessages.value = allMessages.value.slice(start, total);
}
function loadPreviousMessages() {
    const firstMessage = displayedMessages.value[0];
    const index = allMessages.value.findIndex(m => m.id === firstMessage.id);
    if (index <= 0) return;

    const start = Math.max(0, index - messagesPerPage);
    const newMessages = allMessages.value.slice(start, index);
    displayedMessages.value = [...newMessages, ...displayedMessages.value];

    nextTick(() => {
        const container = messagesContainer.value;
        if (container) container.scrollTop = container.scrollHeight / 2;
    });
}

const messagesContainer = ref<HTMLElement | null>(null);
const form = useForm({ content: "", receiver_id: receiver.value?.id || null });

function scrollToBottom() {
    nextTick(() => {
        if (messagesContainer.value)
            messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    });
}

// Changer de conversation
function selectReceiver(userId: number) {
    const u = usersWhoMessaged.value.find(u => u.id === userId);
    if (!u) return;
    receiver.value = u;
    form.receiver_id = u.id;
    u.unread = 0;
    initMessages();
    scrollToBottom();
}

// WebSocket
let ws: WebSocket | null = null;
function connectWS() {
    ws = new WebSocket("ws://localhost:6001");

    ws.onopen = () => {
        ws?.send(JSON.stringify({ type: "init", userId: page.props.auth.user.id }));
    };

    ws.onmessage = (event) => {
        const msg: Message & { type?: string } = JSON.parse(event.data);
        if (msg.type === "message") {
            allMessages.value.push(msg);

            const isCurrentChat =
                receiver.value &&
                (msg.sender_id === receiver.value.id || msg.receiver_id === receiver.value.id);

            if (isCurrentChat) {
                displayedMessages.value.push(msg);
                scrollToBottom();
            } else if (msg.receiver_id === page.props.auth.user.id) {
                const u = usersWhoMessaged.value.find(u => u.id === msg.sender_id);
                if (u) u.unread = (u.unread || 0) + 1;
                else
                    usersWhoMessaged.value.unshift({
                        id: msg.sender_id,
                        name: msg.sender_name || "Utilisateur",
                        email: "",
                        unread: 1,
                    });
            }
        }
    };

    ws.onclose = () => setTimeout(connectWS, 1500);
}

// Envoyer message
function sendMessage() {
    if (!form.content || !form.receiver_id) return;

    const tempMessage: Message = {
        id: Date.now(),
        content: form.content,
        sender_id: page.props.auth.user.id,
        sender_name: page.props.auth.user.name,
        receiver_id: form.receiver_id,
        created_at: new Date().toISOString(),
    };

    allMessages.value.push(tempMessage);
    displayedMessages.value.push(tempMessage);
    scrollToBottom();

    ws?.send(JSON.stringify({ ...tempMessage, type: "message" }));
    form.reset("content");
}

watch(receiver, (newReceiver) => {
    form.receiver_id = newReceiver?.id || null;
    initMessages();
    scrollToBottom();
});

onMounted(() => {
    initMessages();
    connectWS();
    scrollToBottom();
});
</script>

<template>
    <UserLayout>
        <div class="flex min-h-screen bg-gray-100 pt-24">
            <!-- Sidebar fixe -->
            <aside
                class="w-full md:w-1/4 border-r p-4 bg-white shadow-sm rounded-xl overflow-y-auto sticky top-24 h-[calc(100vh-6rem)]">
                <h2 class="text-blue-600 font-bold text-xl mb-4">Conversations</h2>
                <ul>
                    <li v-for="user in usersWhoMessaged" :key="user.id" class="mb-2">
                        <button @click="selectReceiver(user.id)"
                            :class="['w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all', receiver?.id === user.id ? 'bg-blue-100 shadow' : 'hover:bg-gray-100']">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="flex flex-col truncate">
                                <span class="font-medium text-gray-800">{{ user.name }}</span>
                                <span class="text-xs text-gray-500">{{ user.email }}</span>
                            </div>
                            <span v-if="user.unread && user.unread > 0"
                                class="ml-auto text-xs bg-red-500 text-white px-2 py-0.5 rounded-full">
                                {{ user.unread }}
                            </span>
                        </button>
                    </li>
                </ul>
            </aside>

            <!-- Chat -->
            <main class="flex-1 flex flex-col bg-white shadow-inner rounded-tr-xl rounded-br-xl">
                <header
                    class="bg-blue-600 px-6 py-4 shadow-md sticky top-0 z-10 flex items-center justify-between rounded-tl-xl rounded-tr-xl">
                    <h1 class="text-xl font-bold text-white truncate">
                        {{ receiver?.name ? `Discussion avec ${receiver.name}` : 'Sélectionnez un utilisateur' }}
                    </h1>
                    <div v-if="receiver" class="text-sm text-blue-100">En ligne</div>
                </header>

                <section ref="messagesContainer"
                    class="flex-1 overflow-y-auto px-6 py-4 flex flex-col space-y-4 scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-blue-100">
                    <div v-if="allMessages.length > displayedMessages.length" class="flex justify-center mb-2">
                        <button @click="loadPreviousMessages"
                            class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full hover:bg-blue-200 transition">
                            Charger les messages précédents
                        </button>
                    </div>

                    <div v-for="msg in displayedMessages" :key="msg.id" class="flex items-end"
                        :class="msg.sender_id === page.props.auth.user.id ? 'justify-end' : 'justify-start'">
                        <div v-if="msg.sender_id !== page.props.auth.user.id" class="mr-2">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-400 flex items-center justify-center text-white font-semibold text-sm">
                                {{ msg.sender_name?.charAt(0).toUpperCase() || '?' }}
                            </div>
                        </div>
                        <div :class="msg.sender_id === page.props.auth.user.id
                            ? 'bg-blue-500 text-white px-5 py-3 rounded-2xl max-w-md shadow-md'
                            : 'bg-gray-100 text-gray-800 px-5 py-3 rounded-2xl max-w-md shadow-sm'">
                            <div v-if="msg.sender_name && msg.sender_id !== page.props.auth.user.id"
                                class="text-sm font-semibold text-blue-600 mb-1">
                                {{ msg.sender_name }}
                            </div>
                            <div>{{ msg.content }}</div>
                            <div class="text-xs text-right mt-1 text-gray-400">
                                {{ new Date(msg.created_at).toLocaleTimeString([], {
                                    hour: '2-digit', minute: '2-digit'
                                }) }}
                            </div>
                        </div>
                    </div>
                </section>

                <footer v-if="receiver?.id"
                    class="mt-4 flex gap-3 px-6 py-4 bg-white sticky bottom-0 rounded-bl-xl rounded-br-xl shadow-inner">
                    <input v-model="form.content" type="text" placeholder="Écris ton message..."
                        @keyup.enter="sendMessage"
                        class="flex-grow border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white placeholder-gray-400" />
                    <button @click="sendMessage"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-full font-semibold shadow-md">
                        Envoyer
                    </button>
                </footer>
            </main>
        </div>
    </UserLayout>
</template>
