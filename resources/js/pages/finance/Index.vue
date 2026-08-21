<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowDownRight, ArrowUpRight, Landmark, Wallet } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import MiniBarChart from '@/components/MiniBarChart.vue';
import StatKpiCard from '@/components/StatKpiCard.vue';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { index as finance } from '@/routes/finance';

type DepenseRow = {
    id: number;
    categorie: string;
    departement: string;
    montant: string;
    date: string;
};

type PaiementRow = {
    id: number;
    libelle: string;
    type: string;
    montant: string;
    departement: string;
    date: string;
};

defineProps<{
    stats: {
        depensesMois: string;
        entreesMois: string;
        sortiesMois: string;
        budgetPrevu: string;
        budgetUtilise: string;
        budgetRatio: number;
        depensesParJour: { label: string; total: number }[];
    };
    depenses: DepenseRow[];
    paiements: PaiementRow[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Finance',
                href: finance(),
            },
        ],
    },
});
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
        <Head title="Finance" />

        <Heading
            title="Finance"
            description="Suivi des dépenses, des paiements et des budgets"
        />

        <div class="grid gap-4 lg:grid-cols-3">
            <Card
                class="border-sidebar-border/70 bg-linear-to-br from-emerald-500/10 via-card to-card lg:col-span-2 dark:border-sidebar-border"
            >
                <CardHeader>
                    <CardTitle>Budget annuel</CardTitle>
                    <CardDescription>
                        {{ stats.budgetRatio }}% de l’objectif consommé
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <p class="text-3xl font-semibold tabular-nums">
                        {{ stats.budgetUtilise }}
                    </p>
                    <div class="h-2 overflow-hidden rounded-full bg-muted">
                        <div
                            class="h-full rounded-full bg-emerald-500"
                            :style="{ width: `${stats.budgetRatio}%` }"
                        />
                    </div>
                    <p class="text-sm text-muted-foreground">
                        Prévision : {{ stats.budgetPrevu }}
                    </p>
                </CardContent>
            </Card>
            <div class="grid gap-4">
                <StatKpiCard
                    label="Entrées du mois"
                    :value="stats.entreesMois"
                    :icon="ArrowUpRight"
                    tint="bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300"
                />
                <StatKpiCard
                    label="Sorties du mois"
                    :value="stats.sortiesMois"
                    :icon="ArrowDownRight"
                    tint="bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300"
                />
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <StatKpiCard
                label="Dépenses du mois"
                :value="stats.depensesMois"
                :icon="Wallet"
                tint="bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300"
            />
            <StatKpiCard
                label="Budget prévu"
                :value="stats.budgetPrevu"
                :icon="Landmark"
                tint="bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300"
            />
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle>Aperçu hebdomadaire</CardTitle>
                    <CardDescription>Dépenses sur 10 jours</CardDescription>
                </CardHeader>
                <CardContent>
                    <MiniBarChart :points="stats.depensesParJour" />
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Dernières dépenses</CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="depenses.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucune dépense enregistrée.
                    </p>
                    <ul v-else class="divide-y divide-border">
                        <li
                            v-for="depense in depenses"
                            :key="depense.id"
                            class="flex items-center justify-between gap-3 py-3 text-sm first:pt-0 last:pb-0"
                        >
                            <div>
                                <p class="font-medium">{{ depense.categorie }}</p>
                                <p class="text-muted-foreground">
                                    {{ depense.departement }} · {{ depense.date }}
                                </p>
                            </div>
                            <span class="font-semibold tabular-nums"
                                >{{ depense.montant }} €</span
                            >
                        </li>
                    </ul>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Paiements</CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="paiements.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucun paiement enregistré.
                    </p>
                    <ul v-else class="divide-y divide-border">
                        <li
                            v-for="paiement in paiements"
                            :key="paiement.id"
                            class="flex items-center justify-between gap-3 py-3 text-sm first:pt-0 last:pb-0"
                        >
                            <div>
                                <p class="font-medium">{{ paiement.libelle }}</p>
                                <p class="text-muted-foreground">
                                    {{ paiement.date }}
                                </p>
                            </div>
                            <Badge>{{ paiement.type }}</Badge>
                        </li>
                    </ul>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
