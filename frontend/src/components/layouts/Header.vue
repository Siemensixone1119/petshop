<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { LogOut, Menu, ShoppingCart, User, House } from '@lucide/vue';
import Logo from '../ui/Logo.vue';
import SearchField from '../ui/SearchField.vue';
import HeaderAction from '../ui/HeaderAction.vue';
import { useAuth } from '../../composables/useAuth';
import { useCart } from '../../composables/useCart';

const route = useRoute();
const router = useRouter();
const search = ref(route.query.search ?? '');
const { user, isAuthenticated, logout } = useAuth();
const { count, clearCart } = useCart();

const profileLabel = computed(() => user.value?.login || 'Профиль');

watch(
    () => route.query.search,
    value => {
        search.value = value ?? '';
    }
)

const submitSearch = () => {
    const query = { ...route.query };
    const value = search.value.trim();

    if (value) {
        query.search = value;
    } else {
        delete query.search;
    }

    router.push({ name: 'catalog', query });
}

const handleProfile = async () => {
    if (!isAuthenticated.value) {
        router.push({ name: 'login' });
        return;
    }

    router.push({ name: 'profile' });
}

const handleLogout = async () => {
    await logout();
    clearCart();
    router.push({ name: 'main' });
}
</script>

<template>
    <header class="header">
        <div class="header__content container">
            <Logo />
            <SearchField v-model="search" @submit="submitSearch" />
            <div class="header-actions">
                <RouterLink to="/" class="header-action--main">
                    <HeaderAction label="Главное">
                        <House />
                    </HeaderAction>
                </RouterLink>
                <RouterLink to="/catalog">
                    <HeaderAction label="Каталог">
                        <Menu />
                    </HeaderAction>
                </RouterLink>
                <RouterLink to="/cart">
                    <HeaderAction label="Корзина" :count="count">
                        <ShoppingCart />
                    </HeaderAction>
                </RouterLink>
                <HeaderAction :label="profileLabel" @click="handleProfile">
                    <User />
                </HeaderAction>
                <HeaderAction v-if="isAuthenticated" label="Выйти" @click="handleLogout">
                    <LogOut />
                </HeaderAction>
            </div>
        </div>
    </header>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.header {
    background-color: $color-light-bg;
    width: 100%;
    height: fit-content;
    position: sticky;
    top: 0;
    z-index: 1;

    padding: 15px 0;

    &__content {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 0 auto;
        flex: 1;
        gap: 15px;
    }
}

.header-actions {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    background-color: $color-light-bg;
}

.header-action--main {
    display: none;
}

.header-action__icon svg {
    width: 28px;
    height: 28px;
}

@media screen and (max-width: 1200px) {

    .header-action--main {
        display: none;
    }

    .header-action__icon svg {
        width: 24px;
        height: 24px;
    }
}

@media screen and (max-width: 768px) {
    .header-actions {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        border-top: 2px solid $color-line;
        border-bottom: 2px solid $color-line;
    }

    .header-action--main {
        display: flex;
    }
}
</style>
