import Vue from "vue";
//Main pages
import App from "../pages/app.vue";

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");
window.Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: false,
    logToConsole: true
});

const app = new Vue({
    el: "#app",
    components: { App }
});
