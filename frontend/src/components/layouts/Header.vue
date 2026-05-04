<script setup>
import { Menu, ShoppingCart, ShoppingCartIcon, User, House } from '@lucide/vue';
import Logo from '../ui/Logo.vue';
import SearchField from '../ui/SearchField.vue';
import HeaderAction from '../ui/HeaderAction.vue';
</script>

<template>
    <header class="header">
        <div class="header__content container">
            <Logo />
            <SearchField />
            <div class="header-actions">
                <HeaderAction label="Главное" class="header-action--main">
                    <House />
                </HeaderAction>
                <RouterLink to="catalog">
                    <HeaderAction label="Каталог">
                        <Menu />
                    </HeaderAction>
                </RouterLink>
                <HeaderAction label="Корзина" :count="2">
                    <ShoppingCart />
                </HeaderAction>
                <HeaderAction label="Профиль">
                    <User />
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