import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin',
      name: 'home',
      component: HomeView
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('@/components/Events.vue')
    },
    {
        path: '/event/:id',
        name: 'event-edit',
        component:() => import('@/components/EventForm.vue')
    }
  ]
})

export default router
