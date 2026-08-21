<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Users } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { index as rh } from '@/routes/rh';

type EmployeRow = {
    id: number;
    matricule: string;
    nom: string;
    prenom: string;
    poste: string;
    departement: string;
    statut: string;
};

type CongeRow = {
    id: number;
    employe: string;
    type: string;
    dateDebut: string;
    dateFin: string;
};

defineProps<{
    stats: {
        employesActifs: number;
        congesEnAttente: number;
        presencesAujourdhui: number;
    };
    employes: EmployeRow[];
    congesEnAttente: CongeRow[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'RH',
                href: rh(),
            },
        ],
    },
});
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
        <Head title="RH" />

        <Heading
            title="Ressources humaines"
            description="Suivi des employés, des congés et des présences"
        />

        <div class="grid gap-4 md:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Employés actifs
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.employesActifs }}
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Congés en attente
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.congesEnAttente }}
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="text-sm font-medium text-muted-foreground">
                        Présences aujourd’hui
                    </CardTitle>
                </CardHeader>
                <CardContent class="text-2xl font-semibold tabular-nums">
                    {{ stats.presencesAujourdhui }}
                </CardContent>
            </Card>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Users class="size-4" />
                        Derniers employés
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="employes.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucun employé enregistré.
                    </p>
                    <ul v-else class="divide-y divide-border">
                        <li
                            v-for="employe in employes"
                            :key="employe.id"
                            class="flex items-center justify-between gap-3 py-3 text-sm first:pt-0 last:pb-0"
                        >
                            <div>
                                <p class="font-medium">
                                    {{ employe.prenom }} {{ employe.nom }}
                                </p>
                                <p class="text-muted-foreground">
                                    {{ employe.poste }} · {{ employe.departement }}
                                </p>
                            </div>
                            <Badge variant="secondary">{{
                                employe.statut
                            }}</Badge>
                        </li>
                    </ul>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Congés à traiter</CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="congesEnAttente.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucune demande en attente.
                    </p>
                    <ul v-else class="divide-y divide-border">
                        <li
                            v-for="conge in congesEnAttente"
                            :key="conge.id"
                            class="flex items-center justify-between gap-3 py-3 text-sm first:pt-0 last:pb-0"
                        >
                            <div>
                                <p class="font-medium">{{ conge.employe }}</p>
                                <p class="text-muted-foreground">
                                    {{ conge.dateDebut }} → {{ conge.dateFin }}
                                </p>
                            </div>
                            <Badge>{{ conge.type }}</Badge>
                        </li>
                    </ul>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
