<script setup>
import BaseButton from '../components/ui/BaseButton.vue';
import BaseInput from '../components/ui/BaseInput.vue';
</script>

<template>
  <div class="container">
    <div class="login-slogan">
      <h1 class="login-slogan__title">Добро пожаловать в<br>PetShop Online!</h1>
      <span class="login-slogan__text">Всё для счастливых питомцев<br>и заботливых хозяев</span>
    </div>
    <form action="" class="login-form">
      <h2 class="login-form__title">Вход в личный кабинет</h2>
      <BaseInput class="login-form__input" type="email" placeholder="Email" />
      <BaseInput class="login-form__input" type="password" placeholder="Пароль" />

      <div class="login-form__checkbox-wrap">
        <input class="login-form__checkbox" type="checkbox" name="" id="1">
        <label class="login-form__label" for="1">Запомнить меня</label>
      </div>

      <BaseButton class="login-form__btn" label="Войти" />
      <span class="login-form__text">или</span>
      <BaseButton class="login-form__btn" label="Зарегестрироваться" second="second-btn" />
    </form>
  </div>
</template>
<style lang="scss" scoped>
@use "../assets/styles/variables" as *;

.container {
  position: relative;
}

.login-form {
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

  &__btn {
    width: 100%;
  }
}

.login-slogan {
  position: absolute;
  &__title {
    font-size: 32px;
    font-family: $font-title;
    font-weight: 700;
  }

  &__text {
    font-size: 16px;
  }
}
</style>