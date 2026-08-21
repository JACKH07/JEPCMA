<script setup lang="ts">
import type { Component } from 'vue';

defineProps<{
    label: string;
    value: string | number;
    hint?: string;
    icon: Component;
    tint: string;
}>();
</script>

<template>
    <div
        class="rounded-xl border border-sidebar-border/70 bg-card p-5 dark:border-sidebar-border"
    >
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="text-sm text-muted-foreground">{{ label }}</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight tabular-nums">
                    {{ value }}
                </p>
                <p v-if="hint" class="mt-1 text-xs text-muted-foreground">
                    {{ hint }}
                </p>
            </div>
            <div
                class="flex size-12 shrink-0 items-center justify-center rounded-xl"
                :class="tint"
            >
                <component :is="icon" class="size-6" />
            </div>
        </div>
    </div>
</template>
