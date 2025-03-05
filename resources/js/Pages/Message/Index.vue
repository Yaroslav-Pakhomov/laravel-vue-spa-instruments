<template>
    <div class="w-2/3 mx-auto mt-3 text-center"> Сообщения главная</div>
    <div class="w-fullbg-white rounded-lg border p-1 md:p-3 m-10">
        <div class="w-full px-3 mb-2 mt-6">
            <textarea
                class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-400 focus:outline-none focus:bg-white"
                placeholder="Введите сообщение" required v-model="message"></textarea>
        </div>

        <div class="w-full flex justify-end px-3 my-3">
            <input @click.prevent="sendForm" type="submit"
                   class="px-2.5 py-1.5 rounded-md text-white text-sm bg-indigo-500 text-lg" value='Отправить'>
        </div>
    </div>

    <div class="w-fullbg-white rounded-lg border p-1 md:p-3 m-10">
        <h3 class="font-semibold p-1">Обсуждение</h3>
        <template v-for="message in messages">
            <div class="flex flex-col gap-5 m-3">
                <!-- Comment Container -->
                <div>
                    <div class="flex w-full justify-between border rounded-md">

                        <div class="p-3">
                            <div class="flex gap-3 items-center">
                                <img src="https://avatars.githubusercontent.com/u/22263436?v=4"
                                     class="object-cover w-10 h-10 rounded-full border-2 border-emerald-400  shadow-emerald-400">
                                <h3 class="font-bold">
                                    {{ message.user.name }}
                                    <br>
                                    <span class="text-sm text-gray-400 font-normal">{{ message.user.email }} </span>
                                </h3>
                            </div>
                            <p class="text-gray-600 mt-2">
                                ID: {{ message.id }}
                            </p>
                            <p class="text-gray-600 mt-2">
                                Обновлено: {{ message.updated_at }}
                            </p>
                            <p class="text-gray-600 mt-2">
                                Сообщение: {{ message.body }}
                            </p>
                            <button class="text-right text-blue-500">Reply</button>
                        </div>

                    </div>

                    <template v-if="message.children.length > 0">
                        <template v-for="child in message.children">
                            <RecursionTree :tree="child" :ml="ml"></RecursionTree>
                        </template>
                    </template>
                    <br>
                </div>
            </div>
        </template>
    </div>
</template>

<script>

import axios from "axios";
import RecursionTree from "@/Components/RecursionTree.vue";


export default {
    /**
     * Название компонента
     */
    name: 'MessageIndexPage',

    /**
     * Подключенные компоненты
     */
    components: {RecursionTree},

    /**
     * Передаваемые св-ва от родителя и/или от контроллера
     */
    props: {
        messages: Object,
    },

    /**
     * Св-ва компонента
     */
    data() {
        return {
            message: '',
            ml: 20,
        }
    },

    created() {
        window.Echo.channel('store_message')
            .listen('.store_message', (res) => {
                this.messages.unshift(res.message);
                console.log(res.message);
            });
    },

    /**
     * Действия перед загрузкой страницы
     */
    mounted() {
        console.log(this.messages);
    },

    /**
     * Методы компонента
     */
    methods: {
        sendForm() {
            axios.post(this.route('message.storeAsync'), {'body': this.message})
                .then(res => {
                    this.messages.unshift(res.data);
                    this.message = '';
                })
                .catch(error => {
                    console.log(error);
                });

        }
    },

    /**
     * Вычисляемые св-ва компонента
     */
    computed: {},
}

</script>

<style scoped>

</style>
