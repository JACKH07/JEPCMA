<script setup lang="ts">
type Segment = {
    label: string;
    count: number;
    percent: number;
};

const colors = [
    'bg-violet-500',
    'bg-sky-500',
    'bg-cyan-400',
    'bg-zinc-400',
    'bg-emerald-500',
];

const props = defineProps<{
    title: string;
    segments: Segment[];
}>();
</script>

<template>
    <div class="space-y-4">
        <h3 class="font-semibold">{{ title }}</h3>
        <div class="flex h-3 overflow-hidden rounded-full bg-muted">
            <div
                v-for="(segment, index) in props.segments"
                :key="segment.label"
                class="h-full"
                :class="colors[index % colors.length]"
                :style="{ width: `${segment.percent}%` }"
            />
        </div>
        <ul class="space-y-3">
            <li
                v-for="(segment, index) in props.segments"
                :key="segment.label"
                class="flex items-center justify-between gap-3 text-sm"
            >
                <span class="flex items-center gap-2">
                    <span
                        class="size-2.5 rounded-full"
                        :class="colors[index % colors.length]"
                    />
                    {{ segment.label }}
                </span>
                <span class="tabular-nums text-muted-foreground">
                    {{ segment.count }} · {{ segment.percent }}%
                </span>
            </li>
        </ul>
    </div>
</template>
