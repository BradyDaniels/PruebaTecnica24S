'use client'

import Table from "@/components/Table/table";
import React,{ useEffect, useState } from "react";
import BlackBtn from "@/components/buttons/black_btn";
import WhiteBtn from "@/components/buttons/white_btn";




function AddServicioModal({action,actionAdd}){
  const [titulo,setTitulo]=useState('TITLE');
  const [precio,setPrecio]=useState('');

  return <div className="px-[20px] absolute z-[99] h-full w-full bg-black bg-opacity-25 flex flex-col justify-center items-center">
       <div className="p-[20px] flex flex-col justify-start items-center w-full min-h-[350px] max-w-[450px] bg-white rounded-lg border-gray">
          <button onClick={()=>action()} className="relative left-[50%] top-[-10px] text-black">X</button>
          <h2 className="text-black">Nuevo Servicio</h2>
          <div className="px-[40px] w-full h-full mt-[20px]">
              <input onChange={(value)=>setTitulo(value.target.value)} type="text" placeholder="Titulo" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border border-transparent focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <input onChange={(value)=>setPrecio(value.target.value)} type="number" placeholder="Precio" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border border-transparent focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <div className="mt-[50px] w-full flex flex-row justify-between items-center">
                 <BlackBtn action={()=>actionAdd(titulo,precio)} title="Agregar"/>
                 <WhiteBtn action={()=>action()} title="Cancelar"/>
              </div>         
          </div>
       </div>
  </div>
}

function ServicioModal({action,item,actionUpdate,actionDelete}){
  const [titulo,setTitulo]=useState(item.Titulo);
  const [precio,setPrecio]=useState(item.Precio);

  return <div className="px-[20px] absolute z-[99] h-full w-full bg-black bg-opacity-25 flex flex-col justify-center items-center">
       <div className="p-[20px] flex flex-col justify-start items-center w-full min-h-[350px] max-w-[450px] bg-white rounded-lg border-gray">
          <button onClick={()=>action()} className="relative left-[50%] top-[-10px] text-black">X</button>
          <h2 className="text-black">Editar</h2>
          <p className="mt-[30px] text-black text-center">Edita los campos correspondientes a tus servicios</p>
          <div className="px-[40px] w-full h-full mt-[20px]">
              <input defaultValue={item.Titulo} onChange={(value)=>setTitulo(value.target.value)} type="text" placeholder="Titulo" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border border-transparent focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <input defaultValue={item.Precio} onChange={(event)=>setPrecio(event.target.value)} type="number" placeholder="Precio" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border border-transparent focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <div className="mt-[50px] w-full flex flex-row justify-between items-center">
                 <BlackBtn action={()=>actionUpdate(item,titulo,precio)} title="Actualizar"/>
                 <WhiteBtn action={()=>actionDelete(item.ID)} title="Eliminar"/>
              </div>         
          </div>
       </div>
  </div>
}


function CitasModal({action,item,actionUpdate}){
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
              <input min={item.FechaCita} defaultValue={item.FechaCita} onChange={(value)=>setFecha(value.target.value)} type="date" placeholder="Fecha" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <p className="my-[10px] text-black text-center">A la hora:</p>
              <input max={'6:00 PM'} defaultValue={item.HoraCita} onChange={(value)=>setHora(value.target.value)} type="time" placeholder="Hora" className="w-full rounded-lg  text-black border-2 border-gray-500 px-[10px] my-[5px] h-[35px] border border-transparent focus:outline-none focus:ring-2 focus:ring-black-600 focus:border-transparent"/>
              <div className="mt-[50px] w-full flex flex-row justify-between items-center">
                 <BlackBtn action={()=>actionUpdate(item,titulo,fecha,hora)} title="Aprobar"/>
                 <WhiteBtn action={()=>action()} title="Cancelar"/>
              </div>         
          </div>
       </div>
  </div>
}


export default function Dashboard() {
  
  const [showmodal,setShowmodal]=useState(false);
  const [selected,setSelected]=useState({});
  const [selectedOption, setSelectedOption] = useState('misServicios');

  const [servicios, setServicios] = useState([]);
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchServicios = async () => {
      try {
        const response = await fetch('http://localhost/24siete_prueba/api/servicios?IDDoctor=2'); // Ajusta la ruta según tu API
        if (!response.ok) {
          throw new Error('Error al obtener los servicios');
        }
        const data = await response.json();
        console.log(data[0].IDEspecialidad)
        const response_esp = await fetch('http://localhost/24siete_prueba/api/especialidades/'+data[0].IDEspecialidad);
        if (!response_esp.ok) {
          throw new Error('Error al obtener la especialidad');
        }
        const dataEsp = await response_esp.json();
        // Concatenar data con el título de la especialidad
        const serviciosConEsp = data.map(servicio => ({
            ...servicio,
            Especialidad: dataEsp.Titulo // Asegúrate de que dataEsp tenga el índice correcto
        }));

        setServicios(serviciosConEsp);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    const fetchCitas = async () => {
      try {
        const response = await fetch('http://localhost/24siete_prueba/api/citas?IDDoctor=2'); // Ajusta la ruta según tu API
        if (!response.ok) {
          throw new Error('Error al obtener los servicios');
        }
        const data = await response.json();
        
        const citasConPa = await Promise.all(data.map(async (cita) => {
          const response_pa = await fetch('http://localhost/24siete_prueba/api/pacientes/' + cita.PacienteID);
          if (!response_pa.ok) {
              throw new Error('Error al obtener el paciente');
          }
          const dataPa = await response_pa.json();
      
          return {
              ...cita,
              Paciente: dataPa.NombreCompleto, // Nombre completo del paciente
              Status: cita.Estado == 0 ? 'Pendiente' : 'Aprobado' // Estado de la cita
          };
       }));

        setCitas(citasConPa);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    fetchCitas();
    fetchServicios();
  }, []); // El array vacío asegura que se ejecute solo una vez al montar el componente

  const handleChange = (event) => {
      setSelectedOption(event.target.value);
  };

  

  const columns = ['ID', 'Titulo', 'Precio', 'Especialidad'];
  const columns2 = ['#', 'Titulo', 'FechaCita', 'HoraCita','Paciente', 'Status'];

  const getSelectedData=(index)=>{
    setSelected(index);
    setShowmodal(true);
  }

  const updateSelectedServicio=async (item,titulo,precio)=>{
    try {
      console.log(item);
      console.log(titulo);
      console.log(precio);
      const response = await fetch('http://localhost/24siete_prueba/api/servicios/'+item.ID,{
        method: 'PUT',
        headers:{
          'Content-Type':'aplication/json',
        },
        body:JSON.stringify({
          "IDEspecialidad": item.IDEspecialidad,
          "Titulo": titulo,
          "Precio": precio,
          "IDDoctor": item.IDDoctor
        })
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

  const deleteSelectedServicio=async (id)=>{
    try {
      const response = await fetch('http://localhost/24siete_prueba/api/servicios/'+id,{
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

  const addServicio= async (titulo,precio)=>{
    try {
      const response = await fetch('http://localhost/24siete_prueba/api/servicios',{
        method: 'POST',
        headers:{
          'Content-Type':'aplication/json',
        },
        body:JSON.stringify({
          "IDEspecialidad": "3",
          "Titulo": titulo,
          "Precio": precio,
          "IDDoctor": "2"
        })
      }); // Ajusta la ruta según tu API
      if (!response.ok) {
        throw new Error('Error al obtener los servicios');
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setShowmodal(false);
      window.location.reload();
    }
  }

  const updateSelectedCita=async (item,Titulo,FechaCita,HoraCita)=>{
    console.log(item)
    console.log(Titulo)
    console.log(FechaCita)
    console.log(HoraCita)
    try {
      const response = await fetch('http://localhost/24siete_prueba/api/citas/'+item.ID,{
        method: 'PUT',
        headers:{
          'Content-Type':'aplication/json',
        },
        body:JSON.stringify({
          "PacienteID": item.PacienteID,
          "Titulo": Titulo,
          "FechaCita": FechaCita,
          "DoctorID": item.DoctorID,
          "HoraCita": HoraCita,
          "Estado": 1
        })
      }); // Ajusta la ruta según tu API
      console.log(response)
      if (!response.ok) {
        throw new Error('Error al actualizar la cita');
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setShowmodal(false);
    }
  }

  const showModalAddService=(select)=>{
     setSelectedOption(select);
     setShowmodal(true);
  }


  return (
    <div className="p-[40px] w-full h-screen flex flex-col justify-center items-center">
       {showmodal && selectedOption==='nuevoServicio'? <AddServicioModal actionAdd={(a,b)=>addServicio(a,b)} action={()=>setShowmodal(false)}/>: <></>} 
       {showmodal && selectedOption==='misServicios'? <ServicioModal actionDelete={(a)=>deleteSelectedServicio(a)} actionUpdate={(a,b,c)=>updateSelectedServicio(a,b,c)} item={selected} action={()=>setShowmodal(false)}/>: <></>} 
       {showmodal && selectedOption==='misCitasAgendadas'? <CitasModal actionUpdate={(a,b,c,d)=>updateSelectedCita(a,b,c,d)} item={selected} action={()=>setShowmodal(false)}/>: <></>} 
       <h1 className="mb-[30px]">Bienvenido, Doctor</h1>
       <h3 className="mb-[70px]"> Aqui podra ver tanto sus servicios como sus citas agendadas: </h3>
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
             <label htmlFor="services" className="block mr-4">
                Que quieres ver?
             </label>
             <select
                id="services"
                value={selectedOption}
                onChange={handleChange}
                className="mr-[30px] text-black border border-gray-300 rounded-md p-2 w-full max-w-[200px]"
              >
                <option value="misServicios">Mis Servicios</option>
                <option value="misCitasAgendadas">Mis Citas Agendadas</option>
              </select>
              {
                selectedOption==='misServicios' || selectedOption==='nuevoServicio' ? 
                  <BlackBtn action={()=>showModalAddService('nuevoServicio')} title="Nuevo"/>:
                ''
              }    
            </div>
            {
              selectedOption==='misServicios' ?
              <Table action={(i)=>getSelectedData(i)} columns={columns} data={servicios} /> 
              : <Table action={(i)=>getSelectedData(i)} columns={columns2} data={citas} />
            }
            
          </div> : <></>
        }  
       </div>
    </div>
  );
}
