<!-- resources/js/components/Dashboard.vue -->

<template>
  <div>
    <h2>Theses</h2>

    <!-- <ul>
      <li v-for="(item, index) in theses" :key="index">
        {{ item }}
      </li>
    </ul> -->

    <!-- <p>Data received from Blade view: {{ dashboardData  || 'No data provided' }}</p> -->
    <ul>
      <li v-for="(value, key) in dashboardData" :key="key">
        {{ key }}: {{ value }}
      </li>
    </ul>

    <!-- <template>
  <div>
    <v-card v-for="item in dashboardData" :key="item.id">
      <v-card-title>{{ dashboardData.title }}</v-card-title>
      <v-card-subtitle>{{ dashboardData.subtitle }}</v-card-subtitle>
      <v-card-text>{{ dashboardData.description }}</v-card-text>
    </v-card>
  </div>
</template> -->

    <v-app>

    <v-card
      v-for="thesis in dashboardData" :key="thesis.id"
      width="400"
    >
      <template v-slot:title>
        {{ thesis.form_title }}
      </template>

      <template v-slot:subtitle>
        {{ thesis.description }}
      </template>
    </v-card>
  </v-app>

  </div>
</template>

<script type="module">

// import Dashboard from './components/Dashboardtest.vue';
// export default {
//         // components: {
//         //     Dashboard,
//         // },
//         // props: {
//         //   initialData:
//         //    {
//         //     type: String,
//         //     default: '{}', // Default to an empty object if no data is provided
//         //   },
//         // },
//         data() {
//           return {
//             data: [],
//           };
//         },
//         mounted() {

//             console.log("Here is Dashboard Test");
//             console.log(this.data);
//             console.log('Received data:', this.data);

//             axios.get('/testvue')
//                 .then(response => {
//                     this.data = response.data;
//                 })
//                 .catch(error => {
//                 console.error('Error fetching data:', error);
//       });;
//             },
//     };
//     // export default {
//     //   props: {
//     //     data : {
//     //       type: Array,
//     //       required: true,
//     //     },
//     //     // data() {
//     //     //     return {
//     //     //         fetchedData: [],
//     //     //     };
//     //     // },
//     //   },
//     //   mounted() {
//     //     console.log('Received data:', this.data);
//     //     // axios.get('/testvue')
//     //     // .then(response => {
//     //     //     this.fetchedData = response.data;
//     //     // });
//     //   },
//     // };

// export default {
//   props: {
//     data: {
//       type: Array,
//       required: true,
//       default: () => [],
//     },
//   },
//   data() {
//     return {
//       theses: [],
//     };
//   },
//   watch: {
//     data(newData) {
//       // Update the local theses data when the prop changes
//       this.theses = newData;
//     },
//   },
//   created() {
//     // Initially set the local theses data
//     this.theses = this.data;
//   },
// };

export default {
  data() {
    return {
      dashboardData: null,
    };
  },
  created() {
    // Access the data passed from Blade
    this.dashboardData = window.dashboardData;
  },
};
    </script>