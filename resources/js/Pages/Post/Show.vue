<script>

import {usePage} from "@inertiajs/vue3";

export default {
    /**
     * Название компонента
     */
    name: 'ShowIndexPage',

    /**
     * Подключенные компоненты
     */
    components: {},

    /**
     * Передаваемые св-ва от родителя и/или от контроллера
     */
    props: {
        post: Object,
    },

    /**
     * Св-ва компонента
     */
    data() {
        return {}
    },

    mounted() {
        // console.log(this.$data);
        // console.log(this.$props);
        // console.log(this.$el);
        // console.log(this.$options);
        // console.log(this.$parent);
        // console.log(this.$root);
        // console.log(this.$slots);
        // console.log(this.$refs);
        console.log(this.$attrs);
        console.log(this.$page);
        console.log(usePage());
        console.log(usePage().url);
    },

    /**
     * Методы компонента
     */
    methods: {},

    computed: {},
}


</script>

<template>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div
                class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
            >
                <div class="p-6 text-gray-900">
                    You're logged in!
                </div>
            </div>
            <div class="bg-white max-w-2xl shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Данные поста
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                       Детальная информация поста.
                    </p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Название
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ post.title }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Описание
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ post.description }}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Кол-во дней на создание
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ post.days_for_create }}
                            </dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                Ссылка на изображение
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ post.image_url }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>


        </div>
    </div>


</template>

<style scoped>

</style>
