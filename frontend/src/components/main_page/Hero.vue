<script setup>
import BaseButton from '../ui/BaseButton.vue';
</script>

<template>
  <section class="hero">
    <div class="hero__container container">
      <div class="hero__body">
        <h1 class="hero__title">
          Всё для ваших любимцев
        </h1>
        <p class="hero__text">
          Качественные товары для кошек, собак,<br> птиц, грызунов, рыбок и рептилий <br>по выгодным ценам
        </p>
        <RouterLink to="catalog">
          <BaseButton label="Перейти в каталог" />
        </RouterLink>
        <div class="hero__media">
          <img src="../../assets/images/hero/hero1.png" alt="">
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.hero {
  margin-top: 40px;

  &__body {
    background-color: $color-light-bg;
    border-radius: 12px;
    border: 2px solid $color-line;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    background:
      linear-gradient(#fefaf590, #fefaf590),
      url('../../assets/images/bcg/bcg.png');
    background-size: 40%;
    background-position: center;
    position: relative;
  }

  &__title {
    font-family: $font-title;
    font-size: 32px;
    font-weight: 600;
    line-height: 1;
  }

  &__text {
    font-weight: 600;
    font-size: 14px;
  }

  &__button {
    background-color: $color-primary;
    color: $color-light-bg;
    padding: 10px 15px;
    border-radius: 8px;
  }

  &__media {
    width: 340px;
    height: 260px;
    position: absolute;
    bottom: -11.5px;
    right: 10%;
  }

  @media screen and (max-width: 1024px) {
    margin-top: 20px;

    &__media {
      right: 0;
      width: 280px;
      height: 205px;
      bottom: 0;
    }
  }

  @media screen and (max-width: 768px) {
    margin-top: 10px;

    &__media {
      display: none;
    }
  }
}
</style>