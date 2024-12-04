'use client'

import Table from "@/components/Table/table";
import React,{ useEffect, useState } from "react";
import BlackBtn from "@/components/buttons/black_btn";
import WhiteBtn from "@/components/buttons/white_btn";


function CitasModal({action,item,actionDelete}){
  const [titulo,setTitulo]=useState(item.Titulo);
  const [fecha,setFecha]=useState(item.FechaCita);
  const [hora,setHora]=useState(item.HoraCita);

  return <div className="px-[20px] absolute z-[99] h-full w-full bg-black bg-opacity-25 flex flex-col justify-center items-center">
       <div className="p-[20px] flex flex-col justify-start items-center w-full min-h-[350px] max-w-[450px] bg-white rounded-lg border-gray">
          <button onClick={()=>action()} className="relative left-[50%] top-[-10px] text-black">X</button>
          <h2 className="text-black">Agenda de la cita</h2>
          <p className="mt-[30px] text-black text-center">Puedes re agendar la cita o aprobarla directamente</p>
          <div className="px-[40px] w-full h-full mt-[20px]">
              <p className="my-[10px] text-black text-center">Cita pautada para el dia:</p>
              <input disabled min={item.FechaCita} defaultValue={item.FechaCita} onChange={(value)=>setFecha(value.target.value)} type="date" placeholder="Fecha" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <p className="my-[10px] text-black text-center">A la hora:</p>
              <input disabled max={'6:00 PM'} defaultValue={item.HoraCita} onChange={(value)=>setHora(value.target.value)} type="time" placeholder="Hora" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border border-transparent focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <div className="mt-[50px] w-full flex flex-row justify-between items-center">
                 <WhiteBtn action={()=>actionDelete(item.ID)} title="Cancelar"/>
              </div>         
          </div>
       </div>
  </div>
}


export default function MiAgenda() {
  
  const [showmodal,setShowmodal]=useState(false);
  const [selected,setSelected]=useState({});
  const [selectedOption, setSelectedOption] = useState('misCitasAgendadas');

  const [servicios, setServicios] = useState([]);
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {

    const fetchCitas = async () => {
      try {
        const response = await fetch('http://localhost/24siete_prueba/api/citas?IDPaciente=1'); // Ajusta la ruta según tu API
        if (!response.ok) {
          throw new Error('Error al obtener los servicios');
        }
        const data = await response.json();
        
        const citasConDoc = await Promise.all(data.map(async (cita) => {
          const response_doc = await fetch('http://localhost/24siete_prueba/api/doctores/' + cita.DoctorID);
          if (!response_doc.ok) {
              throw new Error('Error al obtener el doctor');
          }
          const dataDoc = await response_doc.json();
      
          return {
              ...cita,
              Doctor: dataDoc.NombreCompleto, // Nombre completo del paciente
              Status: cita.Estado == 0 ? 'Pendiente' : 'Aprobado' // Estado de la cita
          };
       }));

        setCitas(citasConDoc);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    fetchCitas();
  }, []); // El array vacío asegura que se ejecute solo una vez al montar el componente

  
  const columns2 = ['#', 'Titulo', 'FechaCita', 'HoraCita','Doctor', 'Status'];

  const getSelectedData=(index)=>{
    setSelected(index);
    setShowmodal(true);
  }
  
  const deleteSelectedCita=async (id)=>{
    try {
      const response = await fetch('http://localhost/24siete_prueba/api/citas/'+id,{
        method: 'DELETE',
        headers:{
          'Content-Type':'aplication/json',
        },
      }); // Ajusta la ruta según tu API
      if (!response.ok) {
        throw new Error('Error al actualizar los servicios');
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setShowmodal(false);
      window.location.reload();
    }
  }


  const showModalAddService=(select)=>{
     setSelectedOption(select);
     setShowmodal(true);
  }


  return (
    <div className="p-[40px] w-full h-screen flex flex-col justify-center items-center">
       {showmodal && selectedOption==='misCitasAgendadas'? <CitasModal actionDelete={(a)=>deleteSelectedCita(a)} item={selected} action={()=>setShowmodal(false)}/>: <></>} 
       <h1 className="mb-[30px]">Bienvenido, Paciente</h1>
       <h3 className="mb-[70px]"> Aqui podra ver tus citas solicitadas: </h3>
       <div className="w-full flex flex-col justify-center items-center px-[20px]">
        {loading? <div className="flex justify-center items-center h-[340px] mx-auto w-full px-[20px] rounded-lg border border-black-500 pb-[20px] bg-white max-w-[1060px]">
          <p>Cargando....</p>
        </div>:<></>}
        {error? <div className="flex justify-center items-center h-[340px] mx-auto w-full px-[20px] rounded-lg border border-black-500 pb-[20px] bg-white max-w-[1060px]">
          <p>Error: {error}</p>
        </div>:<></>}
        {!loading && !error? 
          <div className="mx-auto w-full px-[20px] rounded-lg border border-black-500 pb-[20px] bg-white max-w-[1060px]">
           <div className="my-5 flex flex-row justify-start items-center">
              <p>Agenda una nueva cita </p>
              <a href="/nueva-cita" className="ml-1"> aqui </a>
            </div>
            <Table action={(i)=>getSelectedData(i)} columns={columns2} data={citas} />
            
          </div> : <></>
        }  
       </div>
    </div>
  );
}
