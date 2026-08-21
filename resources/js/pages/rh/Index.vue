<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CalendarCheck, Clock, Users } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import StatKpiCard from '@/components/StatKpiCard.vue';
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
        totalEmployes: number;
        congesApprouves: number;
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

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <StatKpiCard
                label="Employés actifs"
                :value="stats.employesActifs"
                :hint="`${stats.totalEmployes} fiches`"
                :icon="Users"
                tint="bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300"
            />
            <StatKpiCard
                label="Congés en attente"
                :value="stats.congesEnAttente"
                :icon="CalendarCheck"
                tint="bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300"
            />
            <StatKpiCard
                label="Congés approuvés"
                :value="stats.congesApprouves"
                :icon="CalendarCheck"
                tint="bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300"
            />
            <StatKpiCard
                label="Présences aujourd’hui"
                :value="stats.presencesAujourdhui"
                :icon="Clock"
                tint="bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-300"
            />
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <Card>
                <CardHeader>
                    <CardTitle>Demandes à traiter</CardTitle>
                </CardHeader>
                <CardContent>
                    <p
                        v-if="congesEnAttente.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucune demande en attente.
                    </p>
                    <ul v-else class="space-y-4">
                        <li
                            v-for="conge in congesEnAttente"
                            :key="conge.id"
                            class="flex items-center justify-between gap-3"
                        >
                            <div class="flex size-10 items-center justify-center rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300">
                                <CalendarCheck class="size-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium">
                                    {{ conge.employe }}
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    {{ conge.dateDebut }} → {{ conge.dateFin }}
                                </p>
                            </div>
                            <Badge>{{ conge.type }}</Badge>
                        </li>
                    </ul>
                </CardContent>
            </Card>

            <Card class="lg:col-span-2">
                <CardHeader>
                    <CardTitle>Employés suivis</CardTitle>
                </CardHeader>
                <CardContent class="overflow-x-auto">
                    <p
                        v-if="employes.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        Aucun employé enregistré.
                    </p>
                    <table v-else class="w-full min-w-[36rem] text-left text-sm">
                        <thead class="text-muted-foreground">
                            <tr class="border-b">
                                <th class="pb-3 font-medium">Employé</th>
                                <th class="pb-3 font-medium">Matricule</th>
                                <th class="pb-3 font-medium">Poste</th>
                                <th class="pb-3 font-medium">Département</th>
                                <th class="pb-3 font-medium">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="employe in employes"
                                :key="employe.id"
                                class="border-b last:border-0"
                            >
                                <td class="py-3 font-medium">
                                    {{ employe.prenom }} {{ employe.nom }}
                                </td>
                                <td class="py-3 tabular-nums text-muted-foreground">
                                    {{ employe.matricule }}
                                </td>
                                <td class="py-3">{{ employe.poste }}</td>
                                <td class="py-3">{{ employe.departement }}</td>
                                <td class="py-3">
                                    <Badge variant="secondary">{{
                                        employe.statut
                                    }}</Badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
