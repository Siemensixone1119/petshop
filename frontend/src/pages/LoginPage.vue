<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseButton from '../components/ui/BaseButton.vue';
import BaseInput from '../components/ui/BaseInput.vue';
import { useAuth } from '../composables/useAuth';
import { useCart } from '../composables/useCart';

const route = useRoute();
const router = useRouter();
const { login } = useAuth();
const { loadCart, clearCart } = useCart();

const form = ref({
  email: '',
  password: '',
});
const isSubmitting = ref(false);
const errorMessage = ref('');

const submitLogin = async () => {
  errorMessage.value = '';
  isSubmitting.value = true;

  try {
    await login(form.value);

    try {
      await loadCart();
    } catch {
      clearCart();
    }

    router.push(route.query.redirect || { name: 'main' });
  } catch (error) {
    errorMessage.value = error.response?.data?.code === 'USER_NOT_FOUND' || error.response?.data?.code === 'INVALID_PASSWORD'
      ? 'Неверный email или пароль'
      : 'Не получилось войти. Проверьте данные и попробуйте еще раз.';
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <div class="container">
    <div class="login-slogan">
      <h1 class="login-slogan__title">Добро пожаловать в<br>PetShop Online!</h1>
      <span class="login-slogan__text">Всё для счастливых питомцев<br>и заботливых хозяев</span>
    </div>
    <form class="login-form" @submit.prevent="submitLogin">
      <h2 class="login-form__title">Вход в личный кабинет</h2>
      <BaseInput v-model="form.email" class="login-form__input" type="email" placeholder="Email" required />
      <BaseInput v-model="form.password" class="login-form__input" type="password" placeholder="Пароль" required />

      <div class="login-form__checkbox-wrap">
        <input class="login-form__checkbox" type="checkbox" name="" id="1">
        <label class="login-form__label" for="1">Запомнить меня</label>
      </div>

      <p v-if="errorMessage" class="login-form__error">{{ errorMessage }}</p>
      <BaseButton class="login-form__btn" :label="isSubmitting ? 'Входим...' : 'Войти'" type="submit" :disabled="isSubmitting" />
      <span class="login-form__text">или</span>
      <BaseButton class="login-form__btn" label="Зарегистрироваться" second="second-btn" @click="router.push({ name: 'register' })" />
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

  &__error {
    width: 100%;
    color: $color-primary;
    font-weight: 700;
    text-align: center;
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
