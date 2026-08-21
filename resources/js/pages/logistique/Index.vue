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

defineProps<{
    stats: {
        alertesStock: number;
        commandesEnCours: number;
        livraisonsPlanifiees: number;
    };
    produitsEnAlerte: ProduitRow[];
    commandes: CommandeRow[];
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
            description="Suivi des stocks, des commandes et des livraisons"
        />

        <div class="grid gap-4 md:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Alertes stock
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.alertesStock }}
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Commandes en cours
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.commandesEnCours }}
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Livraisons planifiées
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.livraisonsPlanifiees }}
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <Card>
                <CardHeader>
                    <CardTitle>Produits sous seuil</CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="produitsEnAlerte.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucun produit en alerte.
                    </p>
                    <ul v-else class="divide-y divide-border">
                        <li
                            v-for="produit in produitsEnAlerte"
                            :key="produit.id"
                            class="flex items-center justify-between gap-3 py-3 text-sm first:pt-0 last:pb-0"
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
        </div>
    </div>
</template>
