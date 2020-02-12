<template>
  <div id="app">
    <div v-if="isLoading">
      Loading...
    </div>
    <div v-else-if="error">
      {{ error }}
    </div>
    <div class="container" v-else>
      <aside>
        <Mapbox
          :accessToken="mapboxToken"
          :mapOptions="mapboxOptions"
          @map-load="mapLoaded"
        />
        <Filters @changeFeatureOnOffLeash="changeFeatureOnOffLeash" />
      </aside>
      <main v-if="currentRoute !== 'home'">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
import Filters from './components/Filters.vue';
import Mapbox from 'mapbox-gl-vue';
import Vue from 'vue';

let globalMap = null;

export default {
  components: {
    Filters,
    Mapbox,
  },
  data() {
    return {
      currentRoute: this.$router.currentRoute.name,
      error: false,
      isLoading: true,
      mapboxOptions: {
        center: [174.87, -41.234],
        maxBounds: [
          [165.01859929057042, -47.54977421666661],
          [179.88384589789575, -34.00011395368569],
        ],
        style: 'mapbox://styles/mapbox/streets-v10',
        zoom: 11,
      },
      mapboxToken: this.$appSettings.MAPBOX_TOKEN,
    };
  },
  watch: {
    $route(newVal) {
      this.currentRoute = newVal.name;
      Vue.nextTick(() => {
        globalMap.resize();
        if (newVal.name === 'home') {
          globalMap.flyTo({
            center: [174.87, -41.234],
            zoom: 11,
          });
        } else if (newVal.name === 'park') {
          const currentPark = this.$store.state.parks.find(
            p => p.ID === parseInt(this.$route.params.id, 10),
          );
          globalMap.flyTo({
            center: JSON.parse(currentPark.GeoJson).geometry.coordinates[0][0],
            zoom: 14,
          });
        }
      });
    },
  },
  methods: {
    changeFeatureOnOffLeash(e) {
      ['polygons', 'markers'].forEach((x) => {
        globalMap.setLayoutProperty(
          `on-leash-${x}`,
          'visibility',
          (!e.length || e === 'On-leash') ? 'visible' : 'none',
        );

        globalMap.setLayoutProperty(
          `off-leash-${x}`,
          'visibility',
          (!e.length || e === 'Off-leash') ? 'visible' : 'none',
        );
      });
    },
    mapLoaded(map) {
      globalMap = map;

      map.addControl(new mapboxgl.GeolocateControl({
        positionOptions: {
          enableHighAccuracy: false,
        },
        trackUserLocation: true,
      }));

      // Add polygons and markers to map
      ['On-leash', 'Off-leash'].forEach((type) => {
        map.loadImage(`dog-${type.toLowerCase()}.png`, (error, image) => {
          map.addImage(`dog-${type.toLowerCase()}`, image);

          let polygons = [];
          let markers = [];

          this.$store.state.parks.filter(p => p.FeatureOnOffLeash === type).forEach((park) => {
            const geojson = JSON.parse(park.GeoJson);
            if (!geojson) {
              return;
            }

            polygons.push(geojson);

            markers.push({
              type: 'Feature',
              id: parseInt(park.ID, 10),
              geometry: {
                type: 'Point',
                coordinates: geojson.geometry.coordinates[0][0],
              },
              properties: {
                title: park.Title,
              },
            });
          });

          map.addLayer({
            id: `${type.toLowerCase()}-polygons`,
            type: 'fill',
            source: {
              type: 'geojson',
              data: {
                type: 'FeatureCollection',
                features: polygons,
              },
            },
            paint: {
              'fill-color': '#2ECC40',
            },
          });

          map.addLayer({
            id: `${type.toLowerCase()}-markers`,
            type: 'symbol',
            source: {
              type: 'geojson',
              data: {
                type: 'FeatureCollection',
                features: markers,
              },
            },
            layout: {
              'icon-image': `dog-${type.toLowerCase()}`,
              'icon-size': 0.25,
            },
          });

          map.on('click', `${type.toLowerCase()}-markers`, (e) => {
            this.$router.push(`/park/${e.features[0].id}`);
          });

          map.on('mouseenter', `${type.toLowerCase()}-markers`, () => {
            map.getCanvas().style.cursor = 'pointer';
          });

          map.on('mouseleave', `${type.toLowerCase()}-markers`, () => {
            map.getCanvas().style.cursor = '';
          });
        });
      });
    },
  },
  created() {
    if (!this.$store.state.parks || !this.$store.state.parks.length) {
      this.$store.dispatch('fetchParks').then(() => {
        this.isLoading = false;
        if (this.currentRoute === 'park') {
          const currentPark = this.$store.state.parks.find(
            p => p.ID === parseInt(this.$route.params.id, 10),
          );

          if (currentPark && currentPark.GeoJson.length) {
            this.mapboxOptions.center = JSON.parse(currentPark.GeoJson).geometry.coordinates[0][0];
            this.mapboxOptions.zoom = 14;
          }
        }
      });
    }

    Vue.nextTick(() => {
      window.dispatchEvent(new Event('resize'));
    });
  },
};
</script>

