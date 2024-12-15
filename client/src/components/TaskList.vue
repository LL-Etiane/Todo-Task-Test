<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/apiClient.js';
import Loading from './Loading.vue';
import TaskItem from './TaskItem.vue';

const emit = defineEmits(['edit-task']);

const tasks = ref([]);
const isLoading = ref(true);
const currentPage = ref(1);
const lastPage = ref(1);

const getTasks = async () => {
  isLoading.value = true;
  const response = await apiClient.get('/tasks', {
    params: { page: currentPage.value },
  });
  tasks.value = response.data.data;
  lastPage.value = response.data.last_page;

  isLoading.value = false;
};

const goToPreviousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    getTasks();
  }
};

const editTask = (task) => {
    emit('edit-task', task);
}

const goToNextPage = () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++;
    getTasks();
  }
};

onMounted(() => {
  getTasks();
  console.log('Tasks fetched');
  console.log(tasks.value);
});
</script>

<template>
  <!-- Task Display Section -->
  <section class="w-full bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4 text-center text-gray-800">Tasks List</h2>
    <div v-if="!isLoading" class="space-y-3 text-gray-700">
      <TaskItem v-for="task in tasks" :key="task.id" :task="task" @task-deleted="getTasks" @edit-task="editTask"/>
      
      <!-- Pagination Controls -->
      <div class="flex justify-between mt-4">
        <button @click="goToPreviousPage" :disabled="currentPage === 1" class="btn" :class="{ 'opacity-50': currentPage === 1 }">
          &laquo; Previous
        </button>
        <button @click="goToNextPage" :disabled="currentPage === lastPage" class="btn" :class="{ 'opacity-50': currentPage === lastPage }">
          Next &raquo;
        </button>
      </div>
    </div>
    <Loading v-else />
  </section>
</template>
