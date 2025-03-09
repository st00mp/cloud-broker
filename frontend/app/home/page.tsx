import GpuTable from "../components/GpuTable";

export default async function Home() {
    // Récupère les données côté serveur
    const res = await fetch("http://symfony-nginx/api/gpu/offers", { cache: "no-store" });
    const data = await res.json();

    return (
        <div className="container mx-auto py-8">
            <h1 className="text-2xl font-bold mb-4">GPU Offers</h1>
            <GpuTable data={data} />
        </div>
    );
}