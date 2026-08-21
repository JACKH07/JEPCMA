<script setup lang="ts">
import { computed } from 'vue';

type Point = {
    label: string;
    total: number;
};

const props = defineProps<{
    points: Point[];
}>();

const max = computed(() => Math.max(...props.points.map((point) => point.total), 1));
</script>

<template>
    <div class="flex h-40 items-end gap-2">
        <div
            v-for="point in points"
            :key="point.label"
            class="flex min-w-0 flex-1 flex-col items-center gap-2"
        >
            <div
                class="w-full rounded-t-md bg-primary/80"
                :style="{ height: `${Math.max((point.total / max) * 100, 4)}%` }"
            />
            <span class="text-xs text-muted-foreground">{{ point.label }}</span>
        </div>
    </div>
</template>
