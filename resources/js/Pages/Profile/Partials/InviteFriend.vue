<script setup>
import { usePage, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
    invitations: Array,
});
const user = usePage().props.value.auth.user;
console.log(user);
const confirmInvitation = (id) => {
    useForm({ id }).put(route('user.invitation.update'), {
        preserveScroll: true,
    });
};

const data = ref({
    status: false
});

const onShowAll = () => {
    data.value.status = !data.value.status
}

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium">友人招待</h2>
            <p class="text-sm mt-2">
                友達を紹介してポイントをもらいましょう。<br />
                ユーザー登録時にあなたの招待コードを入力するだけです。
            </p>
            <p class="text-sm mt-2">
                ※ポイントは招待者が入金した際に配布されます
            </p>
        </header>

        <div class="mt-2">
            <span class="w-full px-2 leading-8">{{ user.invite_code }}</span>
        </div>

        <!-- <div class="mt-3 space-y-1">
            <p>招待した友達({{ invitations.length }}人) <button @click="onShowAll" class="px-3 py-1 rounded-full border">すべて表示する</button></p>
            <template v-for="item in invitations" v-if="data.status">
                <div class="flex flex-wrap items-center px-2" v-if="item.status == 1">
                    <span class="px-3 py-1 border rounded-xl">{{ item.email + '(' + item.first_name + item.last_name + ')' }}</span>
                </div>
            </template>
<template v-for="item in invitations">
                <div class="flex flex-wrap items-center px-2" v-if="item.status == 0">
                    <span class="flex-1">{{ item.email + '(' + item.first_name + item.last_name + ')' }}</span>
                    <button class="px-2 py-1 border rounded-md" @click="confirmInvitation(item.id)">ポイント獲得</button>
                </div>
            </template>
</div> -->
    </section>
</template>
