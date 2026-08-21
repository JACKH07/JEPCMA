<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import {
    CircleAlert,
    Clock,
    Package,
    Truck,
    Users,
    Wallet,
} from '@lucide/vue';
import { computed } from 'vue';
import MiniBarChart from '@/components/MiniBarChart.vue';
import StatKpiCard from '@/components/StatKpiCard.vue';
import StatusBreakdown from '@/components/StatusBreakdown.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { dashboard } from '@/routes';
import { index as financeOverview } from '@/routes/finance';
import { index as logistiqueOverview } from '@/routes/logistique';
import { index as rhOverview } from '@/routes/rh';

type Alerte = {
    module: 'rh' | 'finance' | 'logistique';
    message: string;
};

type Segment = {
    label: string;
    value: string;
    count: number;
    percent: number;
};

defineProps<{
    rh: {
        employesActifs: number;
        congesEnAttente: number;
        presencesAujourdhui: number;
        totalEmployes: number;
        congesApprouves: number;
    };
    finance: {
        depensesMois: string;
        entreesMois: string;
        sortiesMois: string;
        budgetPrevu: string;
        budgetUtilise: string;
        budgetRatio: number;
        depensesParJour: { label: string; total: number }[];
    };
    logistique: {
        alertesStock: number;
        commandesEnCours: number;
        livraisonsPlanifiees: number;
        vehiculesEnMission: number;
        vehiculesDisponibles: number;
        vehiculesMaintenance: number;
        commandesParStatut: Segment[];
        vehiculesParStatut: Segment[];
        livraisonsParStatut: Segment[];
    };
    alertes: Alerte[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const page = usePage();
const firstName = computed(
    () => page.props.auth.user.name.split(' ')[0] ?? 'équipe',
);

const moduleHrefs = {
    rh: rhOverview(),
    finance: financeOverview(),
    logistique: logistiqueOverview(),
} as const;
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <Head title="Dashboard" />

        <div class="grid gap-4 lg:grid-cols-3">
            <Card
                class="overflow-hidden border-sidebar-border/70 bg-linear-to-br from-primary/10 via-card to-card lg:col-span-2 dark:border-sidebar-border"
            >
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Félicitations {{ firstName }} 🎉
                    </CardTitle>
                    <CardDescription>
                        Tableau de bord multi-modules RH, Finance et Logistique
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div>
                        <p class="text-3xl font-semibold tabular-nums">
                            {{ finance.budgetUtilise }}
                        </p>
                        <p class="text-sm text-muted-foreground">
                            {{ finance.budgetRatio }}% du budget annuel ({{
                                finance.budgetPrevu
                            }})
                        </p>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-muted">
                        <div
                            class="h-full rounded-full bg-primary"
                            :style="{ width: `${finance.budgetRatio}%` }"
                        />
                    </div>
                    <Button as-child>
                        <Link :href="financeOverview()">Voir la finance</Link>
                    </Button>
                </CardContent>
            </Card>

            <div class="grid gap-4">
                <StatKpiCard
                    label="Employés actifs"
                    :value="rh.employesActifs"
                    :hint="`${rh.totalEmployes} fiches au total`"
                    :icon="Users"
                    tint="bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300"
                />
                <StatKpiCard
                    label="Commandes en cours"
                    :value="logistique.commandesEnCours"
                    :hint="`${logistique.livraisonsPlanifiees} livraisons planifiées`"
                    :icon="Package"
                    tint="bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300"
                />
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <StatKpiCard
                label="Présences aujourd’hui"
                :value="rh.presencesAujourdhui"
                :hint="`${rh.congesEnAttente} congés en attente`"
                :icon="Clock"
                tint="bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300"
            />
            <StatKpiCard
                label="Dépenses du mois"
                :value="finance.depensesMois"
                :hint="`Entrées ${finance.entreesMois}`"
                :icon="Wallet"
                tint="bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300"
            />
            <StatKpiCard
                label="Véhicules en mission"
                :value="logistique.vehiculesEnMission"
                :hint="`${logistique.vehiculesDisponibles} disponibles`"
                :icon="Truck"
                tint="bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300"
            />
            <StatKpiCard
                label="Alertes stock"
                :value="logistique.alertesStock"
                :hint="`${logistique.vehiculesMaintenance} véhicules en maintenance`"
                :icon="CircleAlert"
                tint="bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-300"
            />
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle>Dépenses (10 jours)</CardTitle>
                    <CardDescription>Volume quotidien réel</CardDescription>
                </CardHeader>
                <CardContent>
                    <MiniBarChart :points="finance.depensesParJour" />
                </CardContent>
            </Card>
            <Card>
                <CardContent class="pt-6">
                    <StatusBreakdown
                        title="Aperçu des véhicules"
                        :segments="logistique.vehiculesParStatut"
                    />
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <CircleAlert class="size-5" />
                        Alertes
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="alertes.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucune alerte pour le moment.
                    </p>
                    <ul v-else class="space-y-3">
                        <li
                            v-for="(alerte, index) in alertes"
                            :key="index"
                            class="flex items-center justify-between gap-3 rounded-lg border border-sidebar-border/70 px-3 py-2 dark:border-sidebar-border"
                        >
                            <div class="flex min-w-0 items-center gap-2">
                                <Badge variant="secondary">{{
                                    alerte.module.toUpperCase()
                                }}</Badge>
                                <span class="truncate text-sm">{{
                                    alerte.message
                                }}</span>
                            </div>
                            <Link
                                :href="moduleHrefs[alerte.module]"
                                class="shrink-0 text-sm font-medium text-primary"
                            >
                                Voir
                            </Link>
                        </li>
                    </ul>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
