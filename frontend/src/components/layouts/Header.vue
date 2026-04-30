<script setup>
import { Search } from '@lucide/vue';
import Logo from '../ui/Logo.vue';
import SearchField from '../ui/SearchField.vue';
</script>

<template>
    <div class="header">
        <div class="header__content">
            <Logo />
            <SearchField />
            
        </div>
    </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.header {
    background-color: $color-light-bg;
    width: 100%;
    height: fit-content;

    padding: 15px 0;

    &__content {
        width: $breakpoint-xl;
        display: flex;
        align-items: center;
        margin: 0 auto;
    }
}
</style>