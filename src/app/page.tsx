import Image from "next/image";

export default function Home() {
  return (
    <div className="p-[50px] w-full h-full flex flex-col justify-center items-center ">
       <h1 className="text-center">
          Escoge tu rol
       </h1>
       <div className="p-[50px] mt-[40px] flex flex-row w-full max-w-[480px] h-full justify-between items center space-x-[40px]">
          <a href="/dashboard" className="border border-gray bg-black w-full max-w-[150px] h-full max-h-[150px] flex flex-col justify-center items-center">
               <h2 className="text-white">Medico</h2>
          </a>
          <a href="/mi-agenda" className="border border-gray bg-black w-full max-w-[150px] h-full max-h-[150px] flex flex-col justify-center items-center">
               <h2 className="text-white">Paciente</h2>
          </a>
       </div>
    </div>
  );
}
