<template>
  <section class="section">
    <div class="container">
      <h1 class="title">Dashboard - {{ user.name }}</h1>

      <div id="app" class="container">

        <b-table :data="shorturls"
                 :columns="columns"
                 paginated
                 backend-pagination
                 :total="total"
                 :per-page="perPage"
                 @page-change="onPageChange"
                 aria-next-label="Next page"
                 aria-previous-label="Previous page"
                 aria-page-label="Page"
                 aria-current-label="Current page"
        ></b-table>

      </div>

      <a href="#" @click.prevent="logout">Logout</a>
    </div>
  </section>
</template>

<script>
export default {
  middleware: 'auth',
  data() {
    return {
      shorturls: [],
      total: 0,
      loading: false,
      page: 1,
      perPage: 50,
      columns: [
        {
          field: 'shortened',
          label: 'Shortened URL',
          width: '15%'
        },
        {
          field: 'full_shortened',
          label: 'Full Shortened',
          width: '25%'
        },
        {
          field: 'original_url',
          label: 'Original URL',
          width: '40%'
        },
        {
          field: 'expired_at',
          label: 'Expires At',
          centered: true,
          width: '20%'
        }
      ],
      user: this.$auth.user.data,
    }
  },
  async fetch() {
    const response = await this.$axios.$get('http://localhost/api/short-url')
    this.shorturls = response.data
    this.total = response.total
    this.page = response.current
    this.perPage = response.per_page
  },
  methods: {
    async loadAsyncData() {
      const params = [
        `page=${this.page}`
      ].join('&')

      this.loading = true
      await this.$axios.$get(`http://localhost/api/short-url?${params}`)
        .then(({ data, total }) => {
          console.log(data)
          this.shorturls = data
          this.total = total
          this.loading = false
        })
        .catch((error) => {
          this.data = []
          this.total = 0
          this.loading = false
          throw error
        })
    },
    onPageChange(page) {
      this.page = page
      this.loadAsyncData()
    },
    async logout() {
      await this.$auth.logout()

      this.$router.push('/login')
    },
  },
}
</script>
