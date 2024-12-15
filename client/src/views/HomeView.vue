<script setup>
import { ref } from 'vue';
import TaskList from '@/components/TaskList.vue';
import TaskForm from '@/components/TaskForm.vue';

const showTaskForm = ref(false);
const taskToEdit = ref(null);

const toggleTaskForm = () => {
  showTaskForm.value = !showTaskForm.value;
};

const editTask = (task) => {
    taskToEdit.value = task;
    toggleTaskForm();
}

</script>

<template>
  <main class="flex flex-col items-center min-h-screen p-6">
    <!-- Header Section -->
    <header class="text-center my-10">
      <h1 class="font-bold text-5xl mb-4 text-primary">Bridge Africa Tasks</h1>
      <p class="text-lg text-gray-700">Your task management solution.</p>
    </header>

    <div class="p-4 w-3/5 bg-green-50">
      <button @click="toggleTaskForm" class="bg-primary text-white font-bold py-2 px-4 rounded mb-3">
        {{ showTaskForm ? 'Back Home' : 'Add Task' }}
      </button>
      <!-- Task List Section -->
      <TaskList v-if="!showTaskForm" @edit-task="editTask"/>

      <!-- Conditionally render TaskForm based on state -->
      <TaskForm v-if="showTaskForm" :task="taskToEdit" @task-created="toggleTaskForm" @task-updated="toggleTaskForm"/>
    </div>
  </main>
</template>
