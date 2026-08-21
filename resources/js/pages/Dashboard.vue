<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CircleAlert, Truck, Users, Wallet } from '@lucide/vue';
import DashboardModuleCard from '@/components/DashboardModuleCard.vue';
import { Badge } from '@/components/ui/badge';
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

defineProps<{
    rh: {
        employesActifs: number;
        congesEnAttente: number;
        presencesAujourdhui: number;
    };
    finance: {
        depensesMois: string;
        entreesMois: string;
        sortiesMois: string;
        budgetPrevu: string;
        budgetUtilise: string;
    };
    logistique: {
        alertesStock: number;
        commandesEnCours: number;
        livraisonsPlanifiees: number;
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

const moduleHrefs = {
    rh: rhOverview(),
    finance: financeOverview(),
    logistique: logistiqueOverview(),
} as const;
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
        <Head title="Dashboard" />

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <DashboardModuleCard
                title="RH"
                description="Employés, congés et pointages"
                :href="rhOverview()"
                :icon="Users"
                :metrics="[
                    {
                        label: 'Employés actifs',
                        value: rh.employesActifs,
                    },
                    {
                        label: 'Congés en attente',
                        value: rh.congesEnAttente,
                    },
                    {
                        label: 'Présences aujourd’hui',
                        value: rh.presencesAujourdhui,
                    },
                ]"
            />
            <DashboardModuleCard
                title="Finance"
                description="Dépenses, paiements et budgets"
                :href="financeOverview()"
                :icon="Wallet"
                :metrics="[
                    {
                        label: 'Dépenses du mois',
                        value: finance.depensesMois,
                    },
                    {
                        label: 'Entrées / sorties',
                        value: `${finance.entreesMois} / ${finance.sortiesMois}`,
                    },
                    {
                        label: 'Budget utilisé',
                        value: `${finance.budgetUtilise} / ${finance.budgetPrevu}`,
                    },
                ]"
            />
            <DashboardModuleCard
                title="Logistique"
                description="Stocks, commandes et livraisons"
                :href="logistiqueOverview()"
                :icon="Truck"
                :metrics="[
                    {
                        label: 'Alertes stock',
                        value: logistique.alertesStock,
                    },
                    {
                        label: 'Commandes en cours',
                        value: logistique.commandesEnCours,
                    },
                    {
                        label: 'Livraisons planifiées',
                        value: logistique.livraisonsPlanifiees,
                    },
                ]"
            />
        </div>

        <Card class="flex-1">
            <CardHeader>
                <CardTitle class="flex items-center gap-2">
                    <CircleAlert class="size-5" />
                    Alertes
                </CardTitle>
                <CardDescription>
                    Points d’attention sur les trois modules
                </CardDescription>
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
                        class="flex items-center justify-between gap-4 rounded-lg border border-sidebar-border/70 px-3 py-2 dark:border-sidebar-border"
                    >
                        <div class="flex items-center gap-3">
                            <Badge variant="secondary">{{
                                alerte.module.toUpperCase()
                            }}</Badge>
                            <span class="text-sm">{{ alerte.message }}</span>
                        </div>
                        <Link
                            :href="moduleHrefs[alerte.module]"
                            class="text-sm font-medium text-primary"
                        >
                            Voir
                        </Link>
                    </li>
                </ul>
            </CardContent>
        </Card>
    </div>
</template>
