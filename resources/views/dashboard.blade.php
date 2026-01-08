<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- {{ __("You're logged in!") }} --}}
                        <div class="py-12" x-data="chatHandler({{ auth()->id() }})">
                            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-4">
                                <div class="w-1/3 bg-white p-4 shadow rounded-lg">
                                    <h3 class="font-bold mb-4">Users</h3>
                                    <button @click="setBroadcast()" class="w-full text-left p-2 bg-blue-100 mb-2 rounded">📢 Public Podcast</button>
                                    <template x-for="user in allUsers" :key="user.id">
                                        <button @click="selectUser(user)" class="w-full text-left p-2 hover:bg-gray-100 border-b" x-text="user.name"></button>
                                    </template>
                                </div>

                                <div class="w-2/3 bg-white p-6 shadow rounded-lg flex flex-col h-[500px]">
                                    <h3 class="font-bold border-b pb-2" x-text="activeChatName"></h3>
                                    <div x-ref="messageContainer" class="flex-1 overflow-y-auto my-4 space-y-2 p-2 bg-gray-50">
                                        <template x-for="msg in messages">
                                            <div :class="msg.sender_id == currentUserId ? 'text-right' : 'text-left'">
                                                {{-- <span :class="msg.sender_id == currentUserId ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black'"
                                                    class="inline-block px-4 py-2 rounded-lg" x-text="msg.message"></span> --}}
                                                    <span :class="msg.sender_id == currentUserId ? 'bg-blue-500 text-white' : 'bg-gray-300 text-black'"
                                                        class="inline-block px-4 py-2 rounded-lg"
                                                        x-text="msg.content">
                                                    </span>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex gap-2">
                                        <input x-model="newMessage" @keyup.enter="sendMessage" type="text" class="flex-1 border-gray-300 rounded-lg" placeholder="Type a message...">
                                        <button @click="sendMessage" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- v1 --}}
                        {{--
                        <script>
                            function chatHandler(userId) {
                                return {
                                    currentUserId: userId,
                                    allUsers: @json($users),
                                    activeChat: null,
                                    activeChatName: 'Select a Chat',
                                    messages: [],
                                    newMessage: '',
                                    isBroadcast: false,

                                    init() {
                                        // Listen for Public Podcast
                                        Echo.channel('public-chat')
                                            .listen('MessageSent', (e) => {
                                                if(this.isBroadcast) this.messages.push(e.message);
                                                else alert("New Public Podcast: " + e.message.message);
                                            });

                                        // Listen for Private Messages
                                        Echo.private(`chat.${userId}`)
                                            .listen('MessageSent', (e) => {
                                                if(this.activeChat && e.message.sender_id == this.activeChat.id) {
                                                    this.messages.push(e.message);
                                                } else {
                                                    alert("New message from user " + e.message.sender_id);
                                                }
                                            });
                                    },
                                    setBroadcast() {
                                        this.isBroadcast = true;
                                        this.activeChat = null;
                                        this.activeChatName = "📢 Public Podcast";
                                        this.messages = []; // You could fetch history here
                                    },
                                    selectUser(user) {
                                        this.isBroadcast = false;
                                        this.activeChat = user;
                                        this.activeChatName = user.name;
                                        fetch(`/chat/${user.id}`).then(res => res.json()).then(data => this.messages = data);
                                    },
                                    sendMessage() {
                                        fetch('/messages', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                            body: JSON.stringify({
                                                message: this.newMessage,
                                                receiver_id: this.activeChat ? this.activeChat.id : null,
                                                is_broadcast: this.isBroadcast
                                            })
                                        }).then(res => res.json()).then(data => {
                                            this.messages.push(data);
                                            this.newMessage = '';
                                        });
                                    }
                                }
                            }
                        </script> --}}

                        {{-- v2 --}}
                        {{-- <script>
                            function chatHandler(userId) {
                                return {
                                    currentUserId: userId,
                                    allUsers: @json($users),
                                    activeChat: null,
                                    activeChatName: 'Select a Chat',
                                    messages: [],
                                    newMessage: '',
                                    isBroadcast: false,

                                    init() {
                                        // Listen for Public Podcast
                                        Echo.channel('public-chat')
                                            .listen('MessageSent', (e) => {
                                                // منع التكرار: لا تضف الرسالة إذا كنت أنت المرسل (لأنها أضيفت بالفعل عند الضغط على Send)
                                                if (e.message.sender_id != this.currentUserId) {
                                                    if (this.isBroadcast) {
                                                        this.messages.push(e.message);
                                                    } else {
                                                        // تنبيه بسيط إذا جاءت رسالة عامة وأنت في شات خاص
                                                        console.log("New public message: " + e.message.content);
                                                    }
                                                }
                                            });

                                        // Listen for Private Messages
                                        Echo.private(`chat.${userId}`)
                                            .listen('MessageSent', (e) => {
                                                if (this.activeChat && e.message.sender_id == this.activeChat.id) {
                                                    this.messages.push(e.message);
                                                } else {
                                                    alert("New private message from user " + e.message.sender_id);
                                                }
                                            });
                                    },

                                    setBroadcast() {
                                        this.isBroadcast = true;
                                        this.activeChat = null;
                                        this.activeChatName = "📢 Public Podcast";
                                        // جلب الرسائل القديمة حتى لا تختفي عند الريفرش
                                        fetch('/broadcast/messages')
                                            .then(res => res.json())
                                            .then(data => this.messages = data);
                                    },

                                    selectUser(user) {
                                        this.isBroadcast = false;
                                        this.activeChat = user;
                                        this.activeChatName = user.name;
                                        fetch(`/chat/${user.id}`)
                                            .then(res => res.json())
                                            .then(data => this.messages = data);
                                    },

                                    sendMessage() {
                                        if (!this.newMessage.trim()) return;

                                        fetch('/messages', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                message: this.newMessage,
                                                receiver_id: this.activeChat ? this.activeChat.id : null,
                                                is_broadcast: this.isBroadcast
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            // إضافة الرسالة فوراً للمرسل
                                            this.messages.push(data);
                                            this.newMessage = '';
                                        });
                                    }
                                }
                            }
                        </script> --}}

                        {{-- v3 --}}
                        {{-- <script>
                            function chatHandler(userId) {
                                return {
                                    currentUserId: userId,
                                    allUsers: @json($users),
                                    activeChat: null,
                                    activeChatName: 'Select a Chat',
                                    messages: [],
                                    newMessage: '',
                                    isBroadcast: false,

                                    init() {
                                        Echo.channel('public-chat')
                                            .listen('MessageSent', (e) => {
                                                if (e.message.sender_id != this.currentUserId) {
                                                    if (this.isBroadcast) {
                                                        this.messages.push(e.message);
                                                        this.scrollToBottom(); // سكرول عند استقبال رسالة عامة
                                                    }
                                                }
                                            });

                                        Echo.private(`chat.${userId}`)
                                            .listen('MessageSent', (e) => {
                                                if (this.activeChat && e.message.sender_id == this.activeChat.id) {
                                                    this.messages.push(e.message);
                                                    this.scrollToBottom(); // سكرول عند استقبال رسالة خاصة
                                                } else {
                                                    alert("New private message from user " + e.message.sender_id);
                                                }
                                            });
                                    },

                                    // دالة النزول لآخر رسالة
                                    scrollToBottom() {
                                        this.$nextTick(() => {
                                            const container = this.$refs.messageContainer;
                                            if (container) {
                                                container.scrollTop = container.scrollHeight;
                                            }
                                        });
                                    },

                                    setBroadcast() {
                                        this.isBroadcast = true;
                                        this.activeChat = null;
                                        this.activeChatName = "📢 Public Podcast";
                                        fetch('/broadcast/messages')
                                            .then(res => res.json())
                                            .then(data => {
                                                this.messages = data;
                                                this.scrollToBottom(); // سكرول بعد تحميل رسائل البرودكاست
                                            });
                                    },

                                    selectUser(user) {
                                        this.isBroadcast = false;
                                        this.activeChat = user;
                                        this.activeChatName = user.name;
                                        fetch(`/chat/${user.id}`)
                                            .then(res => res.json())
                                            .then(data => {
                                                this.messages = data;
                                                this.scrollToBottom(); // سكرول بعد تحميل شات المستخدم
                                            });
                                    },

                                    sendMessage() {
                                        if (!this.newMessage.trim()) return;

                                        fetch('/messages', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                message: this.newMessage,
                                                receiver_id: this.activeChat ? this.activeChat.id : null,
                                                is_broadcast: this.isBroadcast
                                            })
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            this.messages.push(data);
                                            this.newMessage = '';
                                            this.scrollToBottom(); // سكرول بعد إرسال رسالتك
                                        });
                                    }
                                }
                            }
                        </script> --}}

                        {{-- v4 --}}
                            <script>
                                function chatHandler(userId) {
                                    return {
                                        currentUserId: userId,
                                        allUsers: @json($users),
                                        activeChat: null,
                                        activeChatName: 'Select a Chat',
                                        messages: [],
                                        newMessage: '',
                                        isBroadcast: false,

                                        init() {
                                            // 1. تشغيل البرودكاست كوضع افتراضي عند فتح الصفحة
                                            this.setBroadcast();

                                            // Listen for Public Podcast
                                            Echo.channel('public-chat')
                                                .listen('MessageSent', (e) => {
                                                    if (e.message.sender_id != this.currentUserId) {
                                                        if (this.isBroadcast) {
                                                            this.messages.push(e.message);
                                                            this.scrollToBottom();
                                                        }
                                                    }
                                                });

                                            // Listen for Private Messages
                                            Echo.private(`chat.${userId}`)
                                                .listen('MessageSent', (e) => {
                                                    if (this.activeChat && e.message.sender_id == this.activeChat.id) {
                                                        this.messages.push(e.message);
                                                        this.scrollToBottom();
                                                    } else {
                                                        // تنبيه بسيط بدلاً من الـ alert المزعج إذا كنت فاتح البرودكاست وجاءتك رسالة خاصة
                                                        console.log("New private message from user " + e.message.sender_id);
                                                    }
                                                });
                                        },

                                        scrollToBottom() {
                                            this.$nextTick(() => {
                                                const container = this.$refs.messageContainer;
                                                if (container) {
                                                    container.scrollTop = container.scrollHeight;
                                                }
                                            });
                                        },

                                        setBroadcast() {
                                            this.isBroadcast = true;
                                            this.activeChat = null;
                                            this.activeChatName = "📢 Public Podcast";

                                            fetch('/broadcast/messages')
                                                .then(res => res.json())
                                                .then(data => {
                                                    this.messages = data;
                                                    this.scrollToBottom();
                                                });
                                        },

                                        selectUser(user) {
                                            this.isBroadcast = false;
                                            this.activeChat = user;
                                            this.activeChatName = user.name;

                                            fetch(`/chat/${user.id}`)
                                                .then(res => res.json())
                                                .then(data => {
                                                    this.messages = data;
                                                    this.scrollToBottom();
                                                });
                                        },

                                        sendMessage() {
                                            if (!this.newMessage.trim()) return;

                                            fetch('/messages', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    message: this.newMessage,
                                                    receiver_id: this.activeChat ? this.activeChat.id : null,
                                                    is_broadcast: this.isBroadcast
                                                })
                                            })
                                            .then(res => res.json())
                                            .then(data => {
                                                this.messages.push(data);
                                                this.newMessage = '';
                                                this.scrollToBottom();
                                            });
                                        }
                                    }
                                }
                            </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
