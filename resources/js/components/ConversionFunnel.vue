<template>
  <div class="space-y-4">
    <!-- Total Calls Bar -->
    <div>
      <div class="flex justify-between text-sm mb-2">
        <span class="text-slate-400">Total Calls</span>
        <span class="text-white font-medium">{{ total }}</span>
      </div>
      <div class="h-12 bg-slate-700/50 rounded-lg overflow-hidden">
        <div
          class="h-full bg-gradient-to-r from-blue-500 to-blue-400 rounded-lg transition-all duration-1000"
          :style="{ width: '100%' }"
        ></div>
      </div>
    </div>

    <!-- Converted Bar -->
    <div>
      <div class="flex justify-between text-sm mb-2">
        <span class="text-slate-400">Converted</span>
        <span class="text-emerald-400 font-medium">{{ converted }} ({{ conversionRate }}%)</span>
      </div>
      <div class="h-12 bg-slate-700/50 rounded-lg overflow-hidden">
        <div
          class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-lg transition-all duration-1000"
          :style="{ width: conversionRate + '%' }"
        ></div>
      </div>
    </div>

    <!-- Missed Bar -->
    <div>
      <div class="flex justify-between text-sm mb-2">
        <span class="text-slate-400">Missed Opportunities</span>
        <span class="text-amber-400 font-medium">{{ missed }} ({{ missedRate }}%)</span>
      </div>
      <div class="h-12 bg-slate-700/50 rounded-lg overflow-hidden">
        <div
          class="h-full bg-gradient-to-r from-amber-500 to-amber-400 rounded-lg transition-all duration-1000"
          :style="{ width: missedRate + '%' }"
        ></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  total: Number,
  converted: Number,
});

const missed = computed(() => props.total - props.converted);

const conversionRate = computed(() => {
  if (props.total === 0) return 0;
  return ((props.converted / props.total) * 100).toFixed(1);
});

const missedRate = computed(() => {
  if (props.total === 0) return 0;
  return ((missed.value / props.total) * 100).toFixed(1);
});
</script>
