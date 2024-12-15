<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'

const year = new Date().getFullYear()

</script>

<template>
  <header class="bg-white shadow-md flex justify-between py-2 px-10">
    <h1 class="font-bold text-xl">TaskApp</h1>
    <nav class="flex space-x-5">
        <RouterLink to="/" class="">
          <i class="fa-solid fa-house fa-xl"></i>
        </RouterLink>
        <RouterLink to="/about">
          <i class="fa-solid fa-circle-info fa-xl"></i>
        </RouterLink>
      </nav>
  </header>

  <RouterView />

  <!-- Footer Section -->
  <footer class="mt-10 text-center text-gray-600">
    <p>&copy; <span>{{ year }}</span> Bridge Africa. All Rights Reserved.</p>
  </footer>
</template>

