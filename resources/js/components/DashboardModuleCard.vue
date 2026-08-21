<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import type { Component } from 'vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

defineProps<{
    title: string;
    description: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon: Component;
    metrics: { label: string; value: string | number }[];
}>();
</script>

<template>
    <Link :href="href" prefetch class="block h-full">
        <Card
            class="h-full transition-colors hover:border-primary/50 hover:bg-muted/30"
        >
            <CardHeader class="flex flex-row items-start justify-between gap-4">
                <div class="space-y-1">
                    <CardTitle>{{ title }}</CardTitle>
                    <CardDescription>{{ description }}</CardDescription>
                </div>
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground"
                >
                    <component :is="icon" class="size-5" />
                </div>
            </CardHeader>
            <CardContent class="space-y-3">
                <div
                    v-for="metric in metrics"
                    :key="metric.label"
                    class="flex items-center justify-between gap-4 text-sm"
                >
                    <span class="text-muted-foreground">{{ metric.label }}</span>
                    <span class="font-semibold tabular-nums">{{
                        metric.value
                    }}</span>
                </div>
                <span
                    class="inline-flex items-center gap-1 text-sm font-medium text-primary"
                >
                    Ouvrir le module
                    <ArrowRight class="size-4" />
                </span>
            </CardContent>
        </Card>
    </Link>
</template>
