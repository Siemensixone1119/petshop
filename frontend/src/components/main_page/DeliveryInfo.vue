<script setup></script>
<template>
  <div class="delivery-info container">
    <ul class="delivery-info__list">
      <li class="delivery-info__item">
        <div class="delivery-info__media">
          <img src="../../../assets/images/delivery/img1.png" alt="">
        </div>
        <div class="delivery-info__body">
          <span class="delivery-info__title">Быстрая доставка</span>
          <span class="delivery-info__description">По всей России от 1 дня</span>
        </div>
      </li>
      <li class="delivery-info__item">
        <div class="delivery-info__media">
          <img src="../../../assets/images/delivery/img2.png" alt="">
        </div>
        <div class="delivery-info__body">
          <span class="delivery-info__title">Гарантия качества</span>
          <span class="delivery-info__description">Только оригинальные товары</span>
        </div>
      </li>
      <li class="delivery-info__item">
        <div class="delivery-info__media">
          <img src="../../../assets/images/delivery/img3.png" alt="">
        </div>
        <div class="delivery-info__body">
          <span class="delivery-info__title">Удобная оплата</span>
          <span class="delivery-info__description">Онлайн или при получении</span>
        </div>
      </li>
      <li class="delivery-info__item">
        <div class="delivery-info__media">
          <img src="../../../assets/images/delivery/img4.png" alt="">
        </div>
        <div class="delivery-info__body">
          <span class="delivery-info__title">Забота о клиентах</span>
          <span class="delivery-info__description">Поддержка 24/7 всегда рядом</span>
        </div>
      </li>
    </ul>
  </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.delivery-info {
  margin-top: 25px;

  &__list {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    background-color: $color-light-bg;
    border-radius: 8px;
    border: 2px solid $color-line;
    padding: 10px;
  }

  &__item {
    display: flex;
    align-items: center;
    gap: 5px;
    width: fit-content;
  }

  &__media {
    width: 50px;
    height: 50px;
  }

  &__title {
    font-weight: 600;
  }

  &__body {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  &__description {
    font-size: 12px;
  }

   @media screen and (max-width: 1024px) {
    &__list{
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media screen and (max-width: 768px) {
    &__list{
      grid-template-columns: repeat(1, 1fr);
    }
  }
}
</style>