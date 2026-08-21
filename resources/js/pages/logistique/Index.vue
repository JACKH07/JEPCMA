<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CircleAlert, Package, Truck, Wrench } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import StatKpiCard from '@/components/StatKpiCard.vue';
import StatusBreakdown from '@/components/StatusBreakdown.vue';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { index as logistique } from '@/routes/logistique';

type ProduitRow = {
    id: number;
    nom: string;
    quantite: number;
    seuilAlerte: number;
};

type CommandeRow = {
    id: number;
    fournisseur: string;
    dateCommande: string;
    statut: string;
};

type LivraisonRow = {
    id: number;
    fournisseur: string;
    vehicule: string;
    dateLivraison: string;
    statut: string;
};

type Segment = {
    label: string;
    value: string;
    count: number;
    percent: number;
};

defineProps<{
    stats: {
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
    produitsEnAlerte: ProduitRow[];
    commandes: CommandeRow[];
    livraisons: LivraisonRow[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Logistique',
                href: logistique(),
            },
        ],
    },
});
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
        <Head title="Logistique" />

        <Heading
            title="Logistique"
            description="Parc véhicules, stocks, commandes et livraisons"
        />

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <StatKpiCard
                label="Véhicules en mission"
                :value="stats.vehiculesEnMission"
                :icon="Truck"
                tint="bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300"
            />
            <StatKpiCard
                label="Véhicules en maintenance"
                :value="stats.vehiculesMaintenance"
                :icon="Wrench"
                tint="bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-300"
            />
            <StatKpiCard
                label="Commandes en cours"
                :value="stats.commandesEnCours"
                :icon="Package"
                tint="bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300"
            />
            <StatKpiCard
                label="Alertes stock"
                :value="stats.alertesStock"
                :icon="CircleAlert"
                tint="bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300"
            />
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <Card>
                <CardContent class="pt-6">
                    <StatusBreakdown
                        title="Aperçu des véhicules"
                        :segments="stats.vehiculesParStatut"
                    />
                </CardContent>
            </Card>
            <Card>
                <CardContent class="pt-6">
                    <StatusBreakdown
                        title="Statut des livraisons"
                        :segments="stats.livraisonsParStatut"
                    />
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle>Produits sous seuil</CardTitle>
                    <CardDescription>Stock à réapprovisionner</CardDescription>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="produitsEnAlerte.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucun produit en alerte.
                    </p>
                    <ul v-else class="space-y-3">
                        <li
                            v-for="produit in produitsEnAlerte"
                            :key="produit.id"
                            class="flex items-center justify-between gap-3 text-sm"
                        >
                            <p class="font-medium">{{ produit.nom }}</p>
                            <span class="tabular-nums text-muted-foreground">
                                {{ produit.quantite }} / {{ produit.seuilAlerte }}
                            </span>
                        </li>
                    </ul>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Dernières commandes</CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="commandes.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucune commande enregistrée.
                    </p>
                    <ul v-else class="divide-y divide-border">
                        <li
                            v-for="commande in commandes"
                            :key="commande.id"
                            class="flex items-center justify-between gap-3 py-3 text-sm first:pt-0 last:pb-0"
                        >
                            <div>
                                <p class="font-medium">
                                    {{ commande.fournisseur }}
                                </p>
                                <p class="text-muted-foreground">
                                    {{ commande.dateCommande }}
                                </p>
                            </div>
                            <Badge variant="secondary">{{
                                commande.statut
                            }}</Badge>
                        </li>
                    </ul>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Livraisons en cours</CardTitle>
                    <CardDescription>
                        {{ stats.livraisonsPlanifiees }} planifiées
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="livraisons.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucune livraison enregistrée.
                    </p>
                    <ul v-else class="space-y-4">
                        <li
                            v-for="livraison in livraisons"
                            :key="livraison.id"
                            class="text-sm"
                        >
                            <p class="font-medium">{{ livraison.fournisseur }}</p>
                            <p class="text-muted-foreground">
                                {{ livraison.vehicule }} ·
                                {{ livraison.dateLivraison }}
                            </p>
                            <Badge variant="secondary" class="mt-1">{{
                                livraison.statut
                            }}</Badge>
                        </li>
                    </ul>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
