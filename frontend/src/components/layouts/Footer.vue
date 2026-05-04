<script setup>
import { Phone, Mail, MapPin, PawPrint } from '@lucide/vue';
import { Icon } from "@iconify/vue";

defineProps({
  categories: {
    type: Object
  }
})
</script>

<template>
  <footer class="footer">
    <div class="footer__container container">
      <div class="footer__content">

        <div class="footer__column">
          <h3 class="footer__title">Категории</h3>

          <ul class="footer__list">
            <li v-for="item in categories" :key="item.id" class="footer__item">
              <PawPrint class="footer__marker" />
              <RouterLink :to="`/catalog/${item.id}`">
                <span class="footer__category-name">
                  {{ item.name }}
                </span>
              </RouterLink>
            </li>
          </ul>
        </div>

        <div class="footer__column">
          <h3 class="footer__title">Контакты</h3>

          <ul class="footer__list">
            <li class="footer__contact">
              <Phone class="footer__icon" />

              <span class="footer__contact-info">
                <a href="tel:88001234567" class="footer__link">
                  8 (800) 123-45-67
                </a>

                <span class="footer__text">
                  Ежедневно с 9:00 до 21:00
                </span>
              </span>
            </li>

            <li class="footer__contact">
              <Mail class="footer__icon" />

              <span class="footer__contact-info">
                <a href="mailto:info@petshop-online.ru" class="footer__link">
                  info@petshop-online.ru
                </a>
              </span>
            </li>

            <li class="footer__contact">
              <MapPin class="footer__icon" />

              <span class="footer__text">
                г. Пенза, ул. Пушкина, д. 137
              </span>
            </li>
          </ul>
        </div>

        <div class="footer__column">
          <h3 class="footer__title">Мы в соцсетях</h3>

          <ul class="footer__socials">
            <li class="footer__social-item">
              <a href="#" class="footer__social-link" aria-label="ВКонтакте">
                <Icon icon="basil:vk-solid" width="24" height="24" />
              </a>
            </li>

            <li class="footer__social-item">
              <a href="#" class="footer__social-link" aria-label="Telegram">
                <Icon icon="mingcute:telegram-fill" width="24" height="24" />
              </a>
            </li>

            <li class="footer__social-item">
              <a href="#" class="footer__social-link" aria-label="YouTube">
                <Icon icon="mdi:youtube" width="24" height="24" />
              </a>
            </li>
          </ul>
        </div>

      </div>

      <div class="footer__bottom">
        &copy; PetShop Онлайн, 2026. Все права защищены.
      </div>
    </div>
  </footer>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.footer {
  background-color: $color-light-bg;
  margin-top: 25px;
  padding-top: 15px;

  &__content {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    justify-items: center;
    padding-bottom: 15px;
  }

  &__title {
    font-family: $font-title;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    text-align: center;
  }

  &__list {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }

  &__item {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  &__marker {
    width: 10px;
    height: 10px;
    fill: currentColor;
    transform: rotate(-45deg);
  }

  &__contact {
    display: flex;
    gap: 10px;
  }

  &__contact-info {
    display: flex;
    flex-direction: column;
  }

  &__icon {
    width: 18px;
    height: 18px;
  }

  &__socials {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  &__social-item {
    background-color: $color-page-bg;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid $color-line;
  }

  &__bottom {
    padding: 10px 0;
    border-top: 1px solid $color-line;
    text-align: center;
  }

  @media screen and (max-width: 576px) {
    &__content {
      grid-template-columns: repeat(1, 1fr);
      gap: 30px;
    }
  }
}
</style>