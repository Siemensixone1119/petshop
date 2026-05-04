<script setup>
import BaseButton from '../components/ui/BaseButton.vue';
import BaseInput from '../components/ui/BaseInput.vue';
</script>

<template>
  <div class="container">
    <div class="register-slogan">
      <h1 class="register-slogan__title">Создайте аккаунт<br>и заботьтесь о питомцах<br>с удовольствием!</h1>
    </div>
    <form action="" class="register-form">
      <h2 class="register-form__title">Создание аккаунта</h2>
      <BaseInput class="register-form__input" type="text" placeholder="Логин" />
      <BaseInput class="register-form__input" type="email" placeholder="Email" />
      <BaseInput class="register-form__input" type="password" placeholder="Пароль" />
      <BaseInput class="register-form__input" type="password" placeholder="Повторите пароль" />

      <div class="register-form__checkbox-wrap">
        <input class="register-form__checkbox" type="checkbox" name="" id="1">
        <label class="register-form__label" for="1">Я согласен с <a href="" class="register-form__link">политикой
            конфидециальности</a></label>
      </div>

      <BaseButton class="register-form__btn" label="Зарегестрироваться" />
      <span class="register-form__text">или</span>
      <BaseButton class="register-form__btn" label="Назад ко входу" second="second-btn" />
    </form>
  </div>
</template>
<style lang="scss" scoped>
@use "../assets/styles/variables" as *;

.container {
  position: relative;
}

.register-form {
  max-width: 430px;
  width: 50%;
  height: 100%;
  min-height: 400px;
  background-color: $color-light-bg;
  border-radius: 12px;
  border: 2px solid $color-line;
  padding: 50px 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  margin: 100px 0;
  margin-left: auto;
  gap: 20px;

  &__title {
    font-size: 20px;
    font-weight: 700;
  }

  &__checkbox-wrap {
    display: flex;
    align-items: center;
    margin-right: auto;
    gap: 10px;
  }

  &__checkbox {
    width: 15px;
    height: 15px;
    accent-color: $color-brand;
  }

  &__link {
    color: $color-primary;
  }

  &__btn {
    width: 100%;
  }
}

.register-slogan {
  position: absolute;

  &__title {
    font-size: 32px;
    font-family: $font-title;
    font-weight: 700;
  }
}
</style>