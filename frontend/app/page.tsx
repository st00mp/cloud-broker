"use client"

import { useState, useEffect } from "react"
import Filters from "./components/Filters"
import { GpuDataTable } from "./components/GpuDataTable"

export default function Home() {

  // États de filtres “avancés”
  const [selectedGPU, setSelectedGPU] = useState("")
  const [selectedProvider, setSelectedProvider] = useState("")
  const [selectedRegion, setSelectedRegion] = useState("")
  const [maxPrice, setMaxPrice] = useState("")

  // Données brutes (issues de l'API) et données filtrées
  const [data, setData] = useState([])

  // Récupération des données (client-side)
  useEffect(() => {
    const fetchData = async () => {
      const res = await fetch("http://localhost:8080/api/gpu/offers", {
        cache: "no-store",
      })
      const jsonData = await res.json()
      console.log("API data: ", jsonData)
      setData(jsonData)
    }
    fetchData()
  }, [])

  // Application des filtres avancés
  const filteredData = data.filter((offer) => {
    return (
      // GPU Type
      ((selectedGPU && selectedGPU !== "All")
        ? offer.gpuModel.includes(selectedGPU)
        : true) &&

      // Provider
      ((selectedProvider && selectedProvider !== "All")
        ? offer.provider.includes(selectedProvider)
        : true) &&

      // Region
      ((selectedRegion && selectedRegion !== "All")
        ? offer.availabilityZone.includes(selectedRegion)
        : true) &&

      // Price
      (maxPrice ? parseFloat(offer.price) <= parseFloat(maxPrice) : true)
    )
  })

  // Exemple de fonction pour exporter
  const exportData = () => {
    // Ici, tu peux convertir `filteredData` en CSV, etc. 
    console.log("Exporting filtered data:", filteredData)
  }

  // Quelques listes d’options
  const gpuTypes = ["All", "A100", "V100", "T4"]
  const providers = ["All", "AWS", "Google Cloud", "Azure"]
  const regions = ["All", "us-east-1", "eu-west-3", "asia-south1"]

  return (
    <div className="container mx-auto py-8">
      <h1 className="text-2xl font-bold m-3 text-center">Train your AI Agent at the Best Price 🚀</h1>

      {/* Filtres avancés (header sticky) */}
      <Filters
        gpuTypes={gpuTypes}
        providers={providers}
        regions={regions}
        setSelectedGPU={setSelectedGPU}
        setSelectedProvider={setSelectedProvider}
        setSelectedRegion={setSelectedRegion}
        setMaxPrice={setMaxPrice}
        exportData={exportData}
      />

      {/* Tableau avec tri (TanStack) sur les colonnes */}
      <div className="px-4 md:px-8">
        <GpuDataTable data={filteredData} />
      </div>
    </div>
  )
}
