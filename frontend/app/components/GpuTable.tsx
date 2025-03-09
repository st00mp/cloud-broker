"use client";

type Offer = {
    id: number;
    provider: string;
    instanceType: string;
    gpuModel: string;
    vram: number;
    vcpu: number;
    price: string;
    availabilityZone: string;
    os_supported: string;
    date: string;
};

export default function GpuTable({ data }: { data: Offer[] }) {
    return (
        <div className="overflow-x-auto p-4">
            <table className="min-w-full bg-white border border-gray-200 shadow-md rounded-md">
                <thead className="bg-gray-900 text-white">
                    <tr>
                        <th className="py-3 px-4 text-left text-gray-200">Provider</th>
                        <th className="py-3 px-4 text-left text-gray-200">Instance Type</th>
                        <th className="py-3 px-4 text-left text-gray-200">GPU Model</th>
                        <th className="py-3 px-4 text-left text-gray-200">VRAM</th>
                        <th className="py-3 px-4 text-left text-gray-200">vCPU</th>
                        <th className="py-3 px-4 text-left text-gray-200">Price</th>
                        <th className="py-3 px-4 text-left text-gray-200">Availabilty Zone</th>
                        <th className="py-3 px-4 text-left text-gray-200">OS</th>
                        <th className="py-3 px-4 text-left text-gray-200">Date</th>
                    </tr>
                </thead>
                <tbody>
                    {data.map((offer) => (
                        <tr key={offer.id} className="border-t border-gray-200 hover:bg-gray-100">
                            <td className="py-3 px-4 text-gray-800">{offer.provider}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.instanceType}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.gpuModel}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.vram}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.vcpu}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.price}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.availabilityZone}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.os_supported}</td>
                            <td className="py-3 px-4 text-gray-800">{offer.date}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}