<script setup>
import { Search } from '@lucide/vue';
</script>

<template>
    <div class="search">
        <form class="search__form" role="search">
            <input class="search__input" type="search" placeholder="Поиск товаров">

            <Search class="search__icon" :size="24" color="#270f0A" aria-hidden="true" />
        </form>
    </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.search {

    &__form {
        position: relative;
        width: 100%;
        height: fit-content;
    }

    &__input {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid $color-line;
    }

    &__input:focus {
        border: 2px solid $color-line;
    }

    &__icon {
        position: absolute;
        bottom: 50%;
        transform: translateY(50%);
        right: 10px;
    }
}
</style>