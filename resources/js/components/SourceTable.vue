<template>
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead>
        <tr class="border-b border-slate-700/50">
          <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">Source</th>
          <th class="text-right py-3 px-4 text-slate-400 font-medium text-sm">Calls</th>
          <th class="text-right py-3 px-4 text-slate-400 font-medium text-sm">Conversions</th>
          <th class="text-right py-3 px-4 text-slate-400 font-medium text-sm">Rate</th>
          <th class="text-right py-3 px-4 text-slate-400 font-medium text-sm">Avg Duration</th>
          <th class="py-3 px-4 text-slate-400 font-medium text-sm w-48">Performance</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(data, source) in sortedSources"
          :key="source"
          class="border-b border-slate-700/30 hover:bg-slate-700/20 transition-colors"
        >
          <td class="py-4 px-4">
            <div class="flex items-center gap-3">
              <div :class="getSourceBadgeClass(source)" class="w-3 h-3 rounded-full"></div>
              <span class="text-white font-medium capitalize">{{ formatSource(source) }}</span>
            </div>
          </td>
          <td class="py-4 px-4 text-right text-slate-300">{{ data.calls }}</td>
          <td class="py-4 px-4 text-right text-emerald-400 font-medium">{{ data.conversions }}</td>
          <td class="py-4 px-4 text-right">
            <span :class="getRateClass(data.rate)" class="font-medium">
              {{ (data.rate * 100).toFixed(1) }}%
            </span>
          </td>
          <td class="py-4 px-4 text-right text-slate-300">
            {{ formatDuration(data.avg_duration_seconds) }}
          </td>
          <td class="py-4 px-4">
            <div class="h-2 bg-slate-700/50 rounded-full overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-500"
                :class="getBarClass(data.rate)"
                :style="{ width: (data.rate * 100) + '%' }"
              ></div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  sources: Object,
});

const sortedSources = computed(() => {
  if (!props.sources) return {};

  return Object.fromEntries(
    Object.entries(props.sources).sort((a, b) => b[1].calls - a[1].calls)
  );
});

const formatSource = (source) => {
  return source.replace(/_/g, ' ');
};

const formatDuration = (seconds) => {
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${mins}:${secs.toString().padStart(2, '0')}`;
};

const getSourceBadgeClass = (source) => {
  const colors = {
    google_ads: 'bg-blue-500',
    website: 'bg-violet-500',
    referral: 'bg-emerald-500',
    direct: 'bg-amber-500',
    social: 'bg-pink-500',
  };
  return colors[source] || 'bg-slate-500';
};

const getRateClass = (rate) => {
  if (rate >= 0.5) return 'text-emerald-400';
  if (rate >= 0.3) return 'text-amber-400';
  return 'text-red-400';
};

const getBarClass = (rate) => {
  if (rate >= 0.5) return 'bg-gradient-to-r from-emerald-500 to-emerald-400';
  if (rate >= 0.3) return 'bg-gradient-to-r from-amber-500 to-amber-400';
  return 'bg-gradient-to-r from-red-500 to-red-400';
};
</script>
