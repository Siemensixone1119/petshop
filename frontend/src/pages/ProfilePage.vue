<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseButton from '../components/ui/BaseButton.vue';
import { useAuth } from '../composables/useAuth';
import { useCart } from '../composables/useCart';

const router = useRouter();
const { user, isAuthenticated, initAuth, logout } = useAuth();
const { clearCart } = useCart();
const isChecking = ref(false);

const handleLogout = async () => {
  await logout();
  clearCart();
  router.push({ name: 'main' });
}

onMounted(async () => {
  if (isAuthenticated.value) {
    return;
  }

  isChecking.value = true;
  await initAuth();
  isChecking.value = false;

  if (!isAuthenticated.value) {
    router.push({ name: 'login', query: { redirect: '/profile' } });
  }
});
</script>

<template>
  <main class="profile-page container">
    <p v-if="isChecking" class="profile-state">Проверяем вход...</p>
    <section v-else-if="isAuthenticated" class="profile-card">
      <h1 class="profile-card__title">Личный кабинет</h1>
      <div class="profile-card__info">
        <span>Логин</span>
        <strong>{{ user?.login }}</strong>
      </div>
      <div class="profile-card__info">
        <span>Email</span>
        <strong>{{ user?.email }}</strong>
      </div>
      <BaseButton label="Выйти из аккаунта" @click="handleLogout" />
    </section>
  </main>
</template>

<style lang="scss" scoped>
@use "../assets/styles/variables" as *;

.profile-page {
  padding-top: 30px;
}

.profile-card {
  max-width: 430px;
  background-color: $color-light-bg;
  border: 2px solid $color-line;
  border-radius: 8px;
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 18px;

  &__title {
    font-family: $font-title;
    font-size: 32px;
  }

  &__info {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid $color-line;
    padding-bottom: 10px;
  }
}

.profile-state {
  max-width: 430px;
  background-color: $color-light-bg;
  border: 2px solid $color-line;
  border-radius: 8px;
  padding: 15px;
}
</style>
