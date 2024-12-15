<script setup>
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import apiClient from '@/apiClient.js'

const props = defineProps(['task']);
const emit = defineEmits(['task-deleted', 'edit-task']);

const isLoading = ref(false);
const isCompleted = ref(false);

onMounted(() => {
    isCompleted.value = props.task.status === 'completed';
});

const editTask = () => {
    emit('edit-task', props.task);
}

const deleteTask = async () => {
    // Show a confirmation dialog before deleting the task
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    });

    if (!result.isConfirmed) {
        return;
    }
    isLoading.value = true;
    try {
        const response = await apiClient.delete(`/tasks/${props.task.id}`);
        // Emit an event to notify the parent component that the task has been deleted
        emit('task-deleted',);
    } catch (error) {
        console.log(error)
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Oops...',
            text: 'There was an error deleting the task',
            showConfirmButton: false,
            timer: 1500
        })
    }

    isLoading.value = false;
}

const updateTaskStatus = async () => {
    isLoading.value = true;
    try {
        const newStatus = props.task.status === 'completed' ? 'pending' : 'completed';
        const response = await apiClient.patch(`/tasks/${props.task.id}/`, { status: newStatus });
        props.task.status = newStatus;
        console.log(response.data);
    } catch (error) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Oops...',
            text: 'There was an error updating task status',
            showConfirmButton: false,
            timer: 1500
        })
        console.log(error)
    }

    isLoading.value = false;
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString();
}
</script>

<template>
    <section class="w-full bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="flex space-x-2">
                <label class="flex items-center">
                <input type="checkbox" v-model="isCompleted" class="mr-2" @change="updateTaskStatus">
            </label>
            <h2 class="text-2xl font-semibold text-gray-800">{{ task.title }}</h2>
            </div>
            <button @click="editTask">
                <i class="fa-solid fa-pen"></i>
            </button>
        </div>

        <p class="text-gray-700 mb-4">{{ task.description }}</p>

        <div class="flex justify-between items-center mt-4">
            <span class="text-sm text-gray-600">Created at: {{ formatDate(task.created_at) }}</span>

            <!-- Delete Button -->
            <button @click="deleteTask" class="bg-red-500 text-white px-2 py-1 rounded">
                Delete
            </button>
        </div>
    </section>
</template>
