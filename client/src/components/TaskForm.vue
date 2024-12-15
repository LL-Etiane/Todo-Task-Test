<script setup>
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import Loading from './Loading.vue';
import apiClient from '@/apiClient.js';

const props = defineProps(['task']);
const emit = defineEmits(['task-created', 'task-updated', 'edit-task']);

// Loading state
const isLoading = ref(false);

// Form fields
const isTaskUpdate = ref(false);
const buttonLabel = ref('Add Task');
const title = ref('');
const description = ref('');
const status = ref('pending');

const errors = ref({
    title: '',
    description: '',
    status: ''
});

onMounted(() => {
    if (props.task) {
        isTaskUpdate.value = true;
        buttonLabel.value = 'Update Task';
        title.value = props.task.title;
        description.value = props.task.description;
        status.value = props.task.status;
    }
});

const validateFormInput = () => {
    errors.value = {
        title: '',
        description: '',
        status: ''
    };

    if (!title.value || title.value.length > 255) {
        errors.value.title = 'Title is required and must be less than 255 characters';
    }

    if (!description.value) {
        errors.value.description = 'Description is required';
    }

    if (!status.value || !['pending', 'completed'].includes(status.value)) {
        errors.value.status = 'Status is required and must be either "pending" or "completed"';
    }

    return !errors.value.title && !errors.value.description && !errors.value.status;
}

// Form submission

const updateTask = async () => {
    if (!validateFormInput()) {
        return;
    }
    isLoading.value = true;
    try {
        const response = await apiClient.patch(`/tasks/${props.task.id}`, {
            title: title.value,
            description: description.value,
            status: status.value
        });

        // Success alert
        Swal.fire({
            position: 'top-end',
            toast: true,
            title: 'Task Updated',
            text: 'Task has been updated successfully',
            icon: 'success',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false
        });

        emit('task-updated');
    } catch (error) {
        console.error('API Error:', error);

        // Error alert
        Swal.fire({
            title: 'Error',
            text: error.response?.data?.message || 'An error occurred while updating the task',
            icon: 'error',
            showConfirmButton: true
        });
    } finally {
        isLoading.value = false;
    }
}

const addTask = async () => {
    console.log(!validateFormInput())
    if (!validateFormInput()) {
        return;
    }
    isLoading.value = true;
    try {
        const response = await apiClient.post('/tasks', {
            title: title.value,
            description: description.value,
            status: status.value
        });

        // Success alert
        Swal.fire({
            position: 'top-end',
            toast: true,
            title: 'Task Added',
            text: 'Task has been added successfully',
            icon: 'success',
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false
        });

        // Clear form fields
        title.value = '';
        description.value = '';
        status.value = 'pending';

        emit('task-created');
    } catch (error) {
        console.error('API Error:', error);

        // Error alert
        Swal.fire({
            title: 'Error',
            text: error.response?.data?.message || 'An error occurred while adding the task',
            icon: 'error',
            showConfirmButton: true
        });
    } finally {
        isLoading.value = false;
    }
}
</script>

<template>
    <section class="w-full bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4 text-center text-gray-800">Add New Task</h2>

        <form @submit.prevent="isTaskUpdate ? updateTask() : addTask()">
            <!-- Title Field -->
            <div class="mb-4">
                <label for="task" class="block text-sm font-medium text-gray-700">Title</label>
                <input 
                    type="text" 
                    name="task" 
                    v-model="title" 
                    id="task" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                />
                <p v-if="errors.title" class="text-red-500 text-sm">{{ errors.title }}</p>
            </div>

            <!-- Description Field -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    v-model="description" 
                    rows="3" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                ></textarea>
                <p v-if="errors.description" class="text-red-500 text-sm">{{ errors.description }}</p>
            </div>

            <!-- Status Field -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select 
                    id="status" 
                    name="status" 
                    v-model="status" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"
                >
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <p v-if="errors.status" class="text-red-500 text-sm">{{ errors.status }}</p>
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button 
                    v-if="!isLoading" 
                    type="submit" 
                    class="w-full flex justify-center space-x-2 py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white bg-primary"
                >
                    <span>{{ buttonLabel }}</span>
                    <span><i class="fa-solid fa-plus"></i></span>
                </button>

                <Loading v-else />
            </div>
        </form>
    </section>
</template>
