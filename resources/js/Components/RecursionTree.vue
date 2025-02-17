<template>
    <!-- Reply Container  -->
    <div class="text-gray-300 font-bold" :style="pl_style">|</div>
    <div class="flex justify-between border rounded-md" :style="ml_style">

        <div class="p-3">
            <div class="flex gap-3 items-center">
                <img src="https://avatars.githubusercontent.com/u/22263436?v=4"
                     class="object-cover w-10 h-10 rounded-full border-2 border-emerald-400  shadow-emerald-400">
                <h3 class="font-bold">
                    {{ tree.user.name }}
                    <br>
                    <span class="text-sm text-gray-400 font-normal">{{ tree.user.email }}</span>
                </h3>
            </div>
            <p class="text-gray-600 mt-2">
                ID: {{ tree.id }}
            </p>
            <p class="text-gray-600 mt-2">
                Сообщение: {{ tree.body }}
            </p>
            <button class="text-right text-blue-500">Reply</button>
        </div>
    </div>
    <template v-if="tree.children && tree.children.length">
        <div>
            <RecursionTree v-for="child in tree.children" :tree="child" :ml="ml_calc"></RecursionTree>
        </div>
    </template>
    <!-- END Reply Container  -->

</template>

<script>
export default {

    name: 'RecursionTree',

    props: {
        tree: Object,
        ml: Number,
    },

    mounted() {
        // console.log(this.ml)
    },

    data() {
        return {
            ml_style: "margin-left: " + this.ml + "px;",
            pl_style: 'padding-left: ' + Number(this.ml + 60) + 'px;',
            // ml_class: "ml-" + this.ml + '',
            ml_calc: Number(this.ml + 20),
        }
    },

    methods: {},
};
</script>

<style scoped>

</style>
