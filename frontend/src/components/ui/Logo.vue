<script setup>
import { PawPrint } from '@lucide/vue';
</script>

<template>
    <RouterLink to="/" class="logo">
        <div class="logo__icon-wrapper">
            <PawPrint color="#ea8386" class="logo__icon" />
        </div>

        <div class="logo__content">
            <span class="logo__title">PetShop Онлайн</span>
            <span class="logo__subtitle">Всё для ваших любимцев</span>
        </div>
    </RouterLink>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.logo {
    display: flex;
    align-items: center;
    gap: 10px;

    &__icon-wrapper {
        background-color: #ea838749;
        border: 1px solid $color-primary;
        border-radius: 50%;
        padding: 8px;
    }

    &__icon {
        fill: $color-primary;
        transform: rotate(-45deg);
        width: 36px;
        height: 36px;
    }

    &__content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    &__title {
        font-family: $font-title;
        font-size: 19px;
        font-weight: 600;
    }

    &__subtitle {
        font-size: 14px;
    }

    @media screen and (max-width: 1024px) {
        &__icon-wrapper {
            padding: 4px;
        }

        &__icon{
            width: 30px;
            height: 30px;
        }

        &__title{
            font-size: 16px;
        }

        &__subtitle{
            font-size: 12px;
        }
    }

    @media screen and (max-width: 576px){
        display: none;
    }
}
</style>