<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
    <!-- Header -->
    <header class="border-b border-slate-700/50 bg-slate-900/50 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white">Conversation Insights</h1>
            <p class="mt-1 text-slate-400">Real-time conversion analytics dashboard</p>
          </div>
          <div class="flex items-center gap-3">
            <span class="flex items-center gap-2 text-sm text-slate-400">
              <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
              Live Data
            </span>
            <button
              @click="fetchMetrics"
              class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors flex items-center gap-2"
              :disabled="loading"
            >
              <svg class="w-4 h-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Refresh
            </button>
          </div>
        </div>
      </div>
    </header>

    <div class="flex flex-col xl:flex-row gap-8 px-4 sm:px-6 lg:px-8 py-8 max-w-[1600px] mx-auto">
      <!-- Main Content -->
      <main class="flex-1 min-w-0">
        <!-- Loading State -->
        <div v-if="loading && !metrics" class="flex items-center justify-center h-64">
          <div class="text-center">
            <svg class="w-12 h-12 text-blue-500 animate-spin mx-auto" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-4 text-slate-400">Loading metrics...</p>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-500/10 border border-red-500/50 rounded-xl p-6 text-center">
          <p class="text-red-400">{{ error }}</p>
          <button @click="fetchMetrics" class="mt-4 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
            Try Again
          </button>
        </div>

        <!-- Dashboard Content -->
        <div v-else-if="metrics">
          <!-- Stats Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <StatCard
              title="Total Calls"
              :value="metrics.total_calls"
              icon="phone"
              color="blue"
            />
            <StatCard
              title="Conversions"
              :value="metrics.total_conversions"
              icon="check"
              color="emerald"
            />
            <StatCard
              title="Missed Opportunities"
              :value="metrics.missed_opportunities"
              icon="x"
              color="amber"
            />
            <StatCard
              title="Conversion Rate"
              :value="formatPercent(metrics.conversion_rate)"
              icon="trending"
              color="violet"
              suffix="%"
            />
          </div>

          <!-- Charts Row -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Conversion Funnel -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
              <h3 class="text-lg font-semibold text-white mb-6">Conversion Funnel</h3>
              <ConversionFunnel
                :total="metrics.total_calls"
                :converted="metrics.total_conversions"
              />
            </div>

            <!-- Average Duration -->
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
              <h3 class="text-lg font-semibold text-white mb-6">Call Statistics</h3>
              <div class="flex items-center justify-center h-48">
                <div class="text-center">
                  <div class="text-6xl font-bold text-white mb-2">
                    {{ formatDuration(metrics.avg_duration_seconds) }}
                  </div>
                  <p class="text-slate-400">Average Call Duration</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Source Breakdown -->
          <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Performance by Source</h3>
            <SourceTable :sources="metrics.by_source" />
          </div>
        </div>
      </main>

      <!-- Sidebar - Simulation Panel -->
      <aside class="xl:w-80 flex-shrink-0">
        <div class="xl:sticky xl:top-8">
          <SimulationPanel @generated="fetchMetrics" @reset="fetchMetrics" />
        </div>
      </aside>
    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-700/50 mt-12 bg-slate-900/50 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
          <!-- Author Info -->
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-violet-500 flex items-center justify-center text-white font-bold text-lg">
              RM
            </div>
            <div>
              <p class="text-white font-semibold">Rafael Moran</p>
              <p class="text-slate-400 text-sm">Full Stack Developer</p>
            </div>
          </div>

          <!-- Tech Stack -->
          <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-red-500/10 text-red-400 rounded-full text-xs font-medium border border-red-500/20">Laravel</span>
            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 rounded-full text-xs font-medium border border-emerald-500/20">Vue.js</span>
            <span class="px-3 py-1 bg-cyan-500/10 text-cyan-400 rounded-full text-xs font-medium border border-cyan-500/20">Tailwind</span>
          </div>

          <!-- Social Links -->
          <div class="flex items-center gap-4">
            <a href="https://github.com/ralphmoran" target="_blank" class="text-slate-400 hover:text-white transition-colors" title="GitHub">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
              </svg>
            </a>
            <a href="https://linkedin.com/in/rafael-moran-74ab9627" target="_blank" class="text-slate-400 hover:text-blue-400 transition-colors" title="LinkedIn">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
            </a>
            <a href="mailto:ralphmoran2003@gmail.com" class="text-slate-400 hover:text-emerald-400 transition-colors" title="Email">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Copyright -->
        <div class="mt-6 pt-6 border-t border-slate-700/50 text-center">
          <p class="text-slate-500 text-sm">
            &copy; {{ new Date().getFullYear() }} Conversation Insights API. Built with passion for Patient Prism.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import StatCard from './StatCard.vue';
import ConversionFunnel from './ConversionFunnel.vue';
import SourceTable from './SourceTable.vue';
import SimulationPanel from './SimulationPanel.vue';

const metrics = ref(null);
const loading = ref(true);
const error = ref(null);

const fetchMetrics = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await fetch('/api/metrics');
    if (!response.ok) throw new Error('Failed to fetch metrics');
    const data = await response.json();
    metrics.value = data.data;
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
};

const formatPercent = (value) => {
  return (value * 100).toFixed(1);
};

const formatDuration = (seconds) => {
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${mins}:${secs.toString().padStart(2, '0')}`;
};

onMounted(() => {
  fetchMetrics();
});
</script>
