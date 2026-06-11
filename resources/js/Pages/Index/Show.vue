<template>
    <div class="container">
        <h1 class="text-3xl font-bold mb-4">Welcome to the Show Page</h1>
        <p class="text-lg">This is the main landing page of the application.</p>
        <Link href="/" class="text-blue-500 hover:underline mt-4 inline-block"
            >Go Back to Home</Link
        >
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
