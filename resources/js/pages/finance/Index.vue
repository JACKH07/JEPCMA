<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
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

        <div class="grid gap-4 md:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Dépenses du mois
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.depensesMois }}
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Entrées / sorties
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.entreesMois }} / {{ stats.sortiesMois }}
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Budget utilisé
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.budgetUtilise }} / {{ stats.budgetPrevu }}
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
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
                    <CardTitle>Derniers paiements</CardTitle>
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
                                    {{ paiement.departement }} · {{ paiement.date }}
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
