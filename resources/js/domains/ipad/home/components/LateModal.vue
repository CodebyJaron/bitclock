<template>
    <h1>Je bent {{ formatMinutes(props.minuten) }} minuten te laat. Deze kan je vandaag of later inhalen.</h1>
</template>

<script setup lang="ts">
import { formatMinutes } from 'helpers/time';


const props = defineProps<{ minuten: number }>();

</script>
