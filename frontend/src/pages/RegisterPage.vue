<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseButton from '../components/ui/BaseButton.vue';
import BaseInput from '../components/ui/BaseInput.vue';
import { useAuth } from '../composables/useAuth';
import { useCart } from '../composables/useCart';

const router = useRouter();
const { register } = useAuth();
const { loadCart, clearCart } = useCart();

const form = ref({
  login: '',
  email: '',
  password: '',
  passwordRepeat: '',
  agree: false,
});
const isSubmitting = ref(false);
const errorMessage = ref('');

const canSubmit = computed(() =>
  form.value.login.trim()
  && form.value.email.trim()
  && form.value.password
  && form.value.passwordRepeat
  && form.value.agree
);

const submitRegister = async () => {
  errorMessage.value = '';

  if (form.value.password !== form.value.passwordRepeat) {
    errorMessage.value = 'Пароли не совпадают';
    return;
  }

  isSubmitting.value = true;

  try {
    await register({
      login: form.value.login,
      email: form.value.email,
      password: form.value.password,
    });

    try {
      await loadCart();
    } catch {
      clearCart();
    }

    router.push({ name: 'main' });
  } catch (error) {
    const errors = error.response?.data?.errors;
    errorMessage.value = errors?.email ? 'Этот email уже занят или указан неверно'
      : errors?.login ? 'Этот логин уже занят'
        : 'Не получилось зарегистрироваться. Проверьте данные.';
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <div class="container">
    <div class="register-slogan">
      <h1 class="register-slogan__title">Создайте аккаунт<br>и заботьтесь о питомцах<br>с удовольствием!</h1>
    </div>
    <form class="register-form" @submit.prevent="submitRegister">
      <h2 class="register-form__title">Создание аккаунта</h2>
      <BaseInput v-model="form.login" class="register-form__input" type="text" placeholder="Логин" required />
      <BaseInput v-model="form.email" class="register-form__input" type="email" placeholder="Email" required />
      <BaseInput v-model="form.password" class="register-form__input" type="password" placeholder="Пароль" required />
      <BaseInput v-model="form.passwordRepeat" class="register-form__input" type="password" placeholder="Повторите пароль" required />

      <div class="register-form__checkbox-wrap">
        <input v-model="form.agree" class="register-form__checkbox" type="checkbox" id="privacy">
        <label class="register-form__label" for="privacy">Я согласен с <a href="" class="register-form__link">политикой
            конфидециальности</a></label>
      </div>

      <p v-if="errorMessage" class="register-form__error">{{ errorMessage }}</p>
      <BaseButton class="register-form__btn" :label="isSubmitting ? 'Создаем...' : 'Зарегистрироваться'" type="submit" :disabled="isSubmitting || !canSubmit" />
      <span class="register-form__text">или</span>
      <BaseButton class="register-form__btn" label="Назад ко входу" second="second-btn" @click="router.push({ name: 'login' })" />
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

  &__error {
    width: 100%;
    color: $color-primary;
    font-weight: 700;
    text-align: center;
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
