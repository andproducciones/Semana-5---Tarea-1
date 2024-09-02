import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, Event } from '@angular/router';
import { IFactura } from 'src/app/Interfaces/factura';
import { ICliente } from 'src/app/Interfaces/icliente';
import { ClientesService } from 'src/app/Services/clientes.service';
import { FacturaService } from 'src/app/Services/factura.service';
import Swal from 'sweetalert2';
@Component({
  selector: 'app-nuevafactura',
  standalone: true,
  imports: [FormsModule, ReactiveFormsModule],
  templateUrl: './nuevafactura.component.html',
  styleUrl: './nuevafactura.component.scss'
})
export class NuevafacturaComponent implements OnInit {
  //variables o constantes
  titulo = 'Nueva Factura';
  listaClientes: ICliente[] = [];
  listaClientesFiltrada: ICliente[] = [];
  totalapagar: number = 0;
  idFactura = 0;
  id: number = 0;
  //formgroup
  frm_factura: FormGroup;

  ///////
  constructor(
    private clietesServicios: ClientesService,
    private facturaService: FacturaService,
    private navegacion: Router,
    private ruta: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.frm_factura = new FormGroup({
      Fecha: new FormControl('', Validators.required),
      Sub_total: new FormControl('', Validators.required),
      Sub_total_iva: new FormControl('', Validators.required),
      Valor_IVA: new FormControl('0.15', Validators.required),
      Clientes_idClientes: new FormControl('', Validators.required)      
    });

    this.clietesServicios.todos().subscribe({
      next: (data) => {
        this.listaClientes = data;
        this.listaClientesFiltrada = data;
      },
      error: (e) => {
        console.log(e);
      }
    });

    this.idFactura = parseInt(this.ruta.snapshot.paramMap.get('idFactura'));
    console.log(this.idFactura);
    if (this.idFactura > 0) {
      this.facturaService.uno(this.idFactura).subscribe((factura) => {
        this.frm_factura.controls['Fecha'].setValue(factura.Fecha);
        this.frm_factura.controls['Sub_total'].setValue(factura.Sub_total);
        this.frm_factura.controls['Sub_total_iva'].setValue(factura.Sub_total_iva);
        this.frm_factura.controls['Valor_IVA'].setValue(factura.Valor_IVA);
        this.frm_factura.controls['Clientes_idClientes'].setValue(factura.Clientes_idClientes);
        this.titulo = 'Editar Factura';
      });
    }
  }

  grabar() {
    let factura: IFactura = {
      idFactura: this.idFactura,
      Fecha: this.frm_factura.get('Fecha')?.value,
      Sub_total: this.frm_factura.get('Sub_total')?.value,
      Sub_total_iva: this.frm_factura.get('Sub_total_iva')?.value,
      Valor_IVA: this.frm_factura.get('Valor_IVA')?.value,
      Clientes_idClientes: this.frm_factura.get('Clientes_idClientes')?.value
    };
    Swal.fire({
      title: 'Factura',
      text: 'Esta seguro que desea grabar la factura!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Grabar Factura'
    }).then((result) => {
      if (result.isConfirmed) {
        this.facturaService.actualizar(factura).subscribe((respuesta) => {
          Swal.fire({
            title: 'Factura',
            text: 'Factura Actualizada',
            icon: 'success',
            confirmButtonText: 'Aceptar'
          });
          this.navegacion.navigate(['/facturas']);
        });
      } else {
        this.facturaService.insertar(factura).subscribe((respuesta) => {
          Swal.fire({
            title: 'Factura',
            text: 'Factura grabada',
            icon: 'success',
            confirmButtonText: 'Aceptar'
          });
        });
      }
    });
  }

  calculos() {
    let sub_total = this.frm_factura.get('Sub_total')?.value;
    let iva = this.frm_factura.get('Valor_IVA')?.value;
    let sub_total_iva = sub_total * iva;
    this.frm_factura.get('Sub_total_iva')?.setValue(sub_total_iva);
    this.totalapagar = parseInt(sub_total) + sub_total_iva;
  }

  cambio(objetoSleect: any) {
    let idCliente = objetoSleect.target.value;
    this.frm_factura.get('Clientes_idClientes')?.setValue(idCliente);
  }
}
