<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const passwordInput = ref<HTMLInputElement | null>(null)

const form = useForm({
    password: ''
})

const deleteUser = async (e: Event) => {
    e.preventDefault()

    const result = await Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Cette action est irréversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d'
    })

    if (result.isConfirmed) {
        form.delete(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => {
                Swal.fire('Compte supprimé', 'Votre compte a bien été supprimé.', 'success')
            },
            onError: () => passwordInput.value?.focus(),
            onFinish: () => form.reset()
        })
    }
}
</script>

<template>
    <div class="px-4">
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 space-y-2">
            <h2 class="text-lg font-semibold text-red-700">⚠️ Attention</h2>
            <p class="text-sm text-red-600">
                Cette action est irréversible. Assurez-vous de vouloir continuer.
            </p>

            <form @submit="deleteUser" class="space-y-5">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Mot de passe
                    </label>
                    <input id="password" type="password" v-model="form.password" ref="passwordInput" required
                        placeholder="********"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-gray-800 py-3 px-4" />
                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing"
                        class="cursor-pointer inline-flex items-center justify-center px-5 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg transition disabled:opacity-50">
                        Supprimer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
