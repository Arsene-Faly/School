import express from "express";
import bodyParser from "body-parser";
import { WebSocketServer } from "ws";

const app = express();
app.use(bodyParser.json());

const clients = new Map(); // userId -> ws

// WS server
const wss = new WebSocketServer({ port: 6001 });
console.log("WS server running on ws://localhost:6001");

wss.on("connection", (ws) => {
  ws.on("message", (msg) => {
    const data = JSON.parse(msg);

    if (data.type === "init") {
      ws.userId = data.userId;
      clients.set(ws.userId, ws);
    }

    if (data.type === "message") {
      const receiver = clients.get(data.receiver_id);
      if (receiver) {
        receiver.send(JSON.stringify({ ...data, type: "message" }));
      }
    }
  });

  ws.on("close", () => {
    if (ws.userId) clients.delete(ws.userId);
  });
});

// HTTP endpoint pour Laravel (optionnel)
app.post("/send", (req, res) => {
  const { message } = req.body;
  const receiver = clients.get(message.receiver_id);
  if (receiver) receiver.send(JSON.stringify({ ...message, type: "message" }));
  res.json({ ok: true });
});

app.listen(6002, () => console.log("HTTP server on :6002"));
