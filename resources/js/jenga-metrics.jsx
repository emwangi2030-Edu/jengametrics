import React, { useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import {
  LineChart, Line, BarChart, Bar, PieChart, Pie, Cell,
  XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend
} from "recharts";

const C = {
  greenDark: "#1B5E35",
  greenMid: "#2E7D52",
  greenBtn: "#1a6b3c",
  greenCard: "#EBF5EE",
  greenCard2: "#d4edda",
  redCard: "#FDECEA",
  redText: "#c0392b",
  page: "#F2F4F2",
  white: "#ffffff",
  border: "#e2e8e4",
  border2: "#c8d5cc",
  text1: "#1a1a1a",
  text2: "#4a4a4a",
  text3: "#888888",
};

const spendData = [
  { month: "Jan", material: 0, labour: 0 },
  { month: "Feb", material: 0, labour: 0 },
  { month: "Mar", material: 180, labour: 95 },
  { month: "Apr", material: 320, labour: 170 },
  { month: "May", material: 410, labour: 210 },
  { month: "Jun", material: 560, labour: 290 },
  { month: "Jul", material: 480, labour: 260 },
  { month: "Aug", material: 390, labour: 200 },
  { month: "Sep", material: 0, labour: 0 },
  { month: "Oct", material: 0, labour: 0 },
  { month: "Nov", material: 0, labour: 0 },
  { month: "Dec", material: 0, labour: 0 },
];

const costBreakdown = [
  { name: "Materials", value: 55, color: "#1a6b3c" },
  { name: "Labour", value: 30, color: "#3498db" },
  { name: "Equipment", value: 15, color: "#f39c12" },
];

const labourWeek = [
  { day: "Mon", skilled: 42, unskilled: 71 },
  { day: "Tue", skilled: 45, unskilled: 74 },
  { day: "Wed", skilled: 44, unskilled: 72 },
  { day: "Thu", skilled: 48, unskilled: 76 },
  { day: "Fri", skilled: 46, unskilled: 74 },
  { day: "Sat", skilled: 30, unskilled: 50 },
];

const projects = [
  { id: "PRJ-001", name: "Riverside Towers", type: "Residential", status: "Active", budget: "1.2M", progress: 78, manager: "James K.", due: "Jun 26" },
  { id: "PRJ-002", name: "Greenfield Estate", type: "Residential", status: "Delayed", budget: "850K", progress: 45, manager: "Sarah M.", due: "Sep 26" },
  { id: "PRJ-003", name: "Central Mall Ext.", type: "Commercial", status: "Planning", budget: "2.1M", progress: 12, manager: "Robert O.", due: "Dec 26" },
  { id: "PRJ-004", name: "Nairobi Hub Office", type: "Commercial", status: "On Hold", budget: "620K", progress: 30, manager: "Grace A.", due: "Mar 27" },
  { id: "PRJ-005", name: "Airport Road Infra", type: "Infrastructure", status: "Completed", budget: "3.4M", progress: 100, manager: "James K.", due: "Feb 26" },
  { id: "PRJ-006", name: "Westpark Villas", type: "Residential", status: "Delayed", budget: "780K", progress: 60, manager: "Peter N.", due: "Aug 26" },
];

const boqData = [
  { ref: "BOQ-001", project: "Riverside Towers", items: 142, total: "1,200,000", status: "Approved" },
  { ref: "BOQ-002", project: "Greenfield Estate", items: 89, total: "850,000", status: "Pending" },
  { ref: "BOQ-003", project: "Central Mall Ext.", items: 217, total: "2,100,000", status: "Draft" },
];

const bomData = [
  { ref: "BOM-001", project: "Riverside Towers", material: "Cement (50kg bags)", qty: 1200, unit: 750, total: "900,000" },
  { ref: "BOM-002", project: "Riverside Towers", material: "Steel Bars (12mm)", qty: 400, unit: 1200, total: "480,000" },
  { ref: "BOM-003", project: "Greenfield Estate", material: "Roofing Sheets", qty: 250, unit: 1800, total: "450,000" },
];

const labourProjects = [
  { name: "Riverside Towers", workers: 48, skilled: 24, rate: "2,880", att: "97%", attOk: true },
  { name: "Greenfield Estate", workers: 35, skilled: 18, rate: "2,100", att: "96%", attOk: true },
  { name: "Central Mall Ext.", workers: 28, skilled: 12, rate: "1,680", att: "89%", attOk: false },
  { name: "Westpark Villas", workers: 32, skilled: 13, rate: "1,920", att: "94%", attOk: true },
];

const reports = [
  { name: "Q1 Financial Summary", type: "Financial", period: "Jan-Mar 2026", gen: "15 Mar 2026", status: "Ready" },
  { name: "Labour Utilisation Report", type: "Workforce", period: "March 2026", gen: "10 Mar 2026", status: "Ready" },
  { name: "Material Stock Report", type: "Inventory", period: "March 2026", gen: "-", status: "Processing" },
];

const requisitionsData = [
  { no: "9182736-RQS", material: "Cement (50kg bags)", qty: 120, section: "Substructure", status: "Pending", requestedBy: "Alex Mutua" },
  { no: "8291736-RQS", material: "Steel Bars (12mm)", qty: 75, section: "Columns", status: "Approved", requestedBy: "Grace Atieno" },
  { no: "7192836-RQS", material: "Binding Wire", qty: 30, section: "Slab Works", status: "Rejected", requestedBy: "Peter N." },
];

const deliveredData = [
  { material: "Cement (50kg bags)", requisitioned: 150, received: 140, variance: -10, uom: "Bags", unitPrice: 750, supplier: "BuildMart Ltd", date: "2026-03-14" },
  { material: "Steel Bars (12mm)", requisitioned: 90, received: 95, variance: 5, uom: "Pcs", unitPrice: 1200, supplier: "MetalHub", date: "2026-03-13" },
  { material: "Roofing Sheets", requisitioned: 200, received: 200, variance: 0, uom: "Pcs", unitPrice: 1800, supplier: "TopRoof", date: "2026-03-10" },
];

const inventoryData = [
  { material: "Cement (50kg bags)", uom: "Bags", stock: 480 },
  { material: "Steel Bars (12mm)", uom: "Pcs", stock: 220 },
  { material: "Binding Wire", uom: "Kg", stock: 75 },
  { material: "Roofing Sheets", uom: "Pcs", stock: 52 },
];

const stockUsageData = [
  { date: "2026-03-18", material: "Cement (50kg bags)", qty: 35, section: "Ground Slab" },
  { date: "2026-03-18", material: "Steel Bars (12mm)", qty: 18, section: "Columns" },
  { date: "2026-03-17", material: "Binding Wire", qty: 5, section: "Slab Works" },
  { date: "2026-03-16", material: "Roofing Sheets", qty: 12, section: "Roofing" },
];

const statusBadge = (s) => {
  const map = {
    Active: { bg: "#e8f5e9", color: "#2e7d32" },
    Completed: { bg: "#e8f5e9", color: "#1b5e20" },
    Ready: { bg: "#e8f5e9", color: "#2e7d32" },
    Approved: { bg: "#e8f5e9", color: "#2e7d32" },
    Delayed: { bg: "#fff8e1", color: "#f57f17" },
    Pending: { bg: "#fff8e1", color: "#f57f17" },
    Processing: { bg: "#fff8e1", color: "#f57f17" },
    Planning: { bg: "#e3f2fd", color: "#1565c0" },
    Draft: { bg: "#e3f2fd", color: "#1565c0" },
    "On Hold": { bg: "#f5f5f5", color: "#616161" },
  };
  const style = map[s] || { bg: "#f5f5f5", color: "#616161" };
  return (
    <span style={{ display: "inline-flex", padding: "3px 9px", borderRadius: 20, fontSize: 10, fontWeight: 700, background: style.bg, color: style.color, whiteSpace: "nowrap" }}>
      {s}
    </span>
  );
};

const progressColor = (p) => (p === 100 ? "#2e7d32" : p >= 60 ? "#1a6b3c" : p >= 30 ? "#f39c12" : "#9e9e9e");

const SectionBar = ({ title, action, onAction }) => (
  <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", padding: "14px 20px", borderBottom: `1px solid ${C.border}` }}>
    <div style={{ display: "flex", alignItems: "center", gap: 8 }}>
      <div style={{ width: 4, height: 16, borderRadius: 2, background: C.greenBtn, flexShrink: 0 }} />
      <span style={{ fontSize: 12, fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.06em", color: C.text1 }}>{title}</span>
    </div>
    {action && <button onClick={onAction} style={{ fontSize: 11, color: C.greenMid, background: "none", border: "none", cursor: "pointer", fontWeight: 600 }}>{action}</button>}
  </div>
);

const Card = ({ children, style }) => (
  <div style={{ background: C.white, borderRadius: 14, border: `1px solid ${C.border}`, overflow: "hidden", ...style }}>{children}</div>
);

const StatCard = ({ icon, val, label, sub, variant = "green" }) => {
  const isPink = variant === "pink";
  return (
    <div style={{ background: isPink ? C.redCard : C.greenCard, borderRadius: 14, padding: "20px", position: "relative", overflow: "hidden", border: `1px solid ${C.border}`, flex: 1 }}>
      <div style={{ position: "absolute", top: -20, right: -20, width: 90, height: 90, borderRadius: "50%", background: isPink ? "#e74c3c" : C.greenMid, opacity: 0.15 }} />
      <div style={{ fontSize: 22, marginBottom: 10 }}>{icon}</div>
      <div style={{ fontSize: 26, fontWeight: 800, lineHeight: 1, letterSpacing: "-0.03em", marginBottom: 6, color: isPink ? C.redText : C.greenDark }}>{val}</div>
      <div style={{ fontSize: 10, fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.08em", color: C.text2, marginBottom: 2 }}>{label}</div>
      <div style={{ fontSize: 10, color: isPink ? C.redText : C.text3 }}>{sub}</div>
    </div>
  );
};

const PriBtn = ({ children, onClick, style, disabled, type = "button" }) => (
  <button
    type={type}
    disabled={disabled}
    onClick={onClick}
    style={{
      padding: "8px 16px",
      background: C.greenBtn,
      border: "none",
      borderRadius: 8,
      fontSize: 12,
      fontWeight: 600,
      color: "#fff",
      cursor: disabled ? "not-allowed" : "pointer",
      display: "flex",
      alignItems: "center",
      gap: 6,
      whiteSpace: "nowrap",
      opacity: disabled ? 0.65 : 1,
      ...style,
    }}
  >
    {children}
  </button>
);

const SecBtn = ({ children, onClick, disabled, type = "button" }) => (
  <button
    type={type}
    disabled={disabled}
    onClick={onClick}
    style={{
      padding: "7px 14px",
      background: C.white,
      border: `1.5px solid ${C.border2}`,
      borderRadius: 8,
      fontSize: 12,
      fontWeight: 600,
      color: C.text1,
      cursor: disabled ? "not-allowed" : "pointer",
      whiteSpace: "nowrap",
      opacity: disabled ? 0.65 : 1,
    }}
  >
    {children}
  </button>
);

const Th = ({ children }) => (
  <th style={{ padding: "10px 20px", textAlign: "left", fontWeight: 700, color: C.text3, fontSize: "9.5px", textTransform: "uppercase", letterSpacing: "0.08em", borderBottom: `1px solid ${C.border}`, background: C.page, whiteSpace: "nowrap" }}>{children}</th>
);

const Td = ({ children, style }) => (
  <td style={{ padding: "12px 20px", color: C.text2, borderBottom: `1px solid ${C.border}`, ...style }}>{children}</td>
);

function DashboardView({ onNewProject, summary }) {
  const kpis = summary?.kpis || {};
  const formatKes = (value) => `KES ${Number(value || 0).toLocaleString()}`;
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 18 }}>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4,minmax(0,1fr))", gap: 14 }}>
        <StatCard icon="⚖️" val={kpis.active_projects ?? 1} label="Active Projects" sub={summary?.project?.name || "Current project"} />
        <StatCard icon="💰" val={formatKes(kpis.total_budget)} label="Total Budget" sub="project budget" />
        <StatCard icon="📊" val={formatKes(kpis.total_spent)} label="Total Spent" sub={`${summary?.progress?.completion_percent ?? 0}% completion`} />
        <StatCard icon="🧾" val={kpis.pending_invoices ?? 0} label="Pending Invoices" sub="awaiting processing" variant="pink" />
      </div>

      <div style={{ display: "grid", gridTemplateColumns: "1fr 310px", gap: 16 }}>
        <Card>
          <SectionBar title="Monthly Spend - 2026 (KES M)" action="Export →" />
          <div style={{ padding: "16px 20px 0", height: 210 }}>
            <ResponsiveContainer width="100%" height="100%">
              <LineChart data={spendData} margin={{ top: 4, right: 8, left: -20, bottom: 0 }}>
                <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.05)" />
                <XAxis dataKey="month" tick={{ fontSize: 10, fill: C.text3 }} axisLine={false} tickLine={false} />
                <YAxis tick={{ fontSize: 10, fill: C.text3 }} axisLine={false} tickLine={false} />
                <Tooltip contentStyle={{ fontSize: 11, borderRadius: 8, border: `1px solid ${C.border}` }} />
                <Line type="monotone" dataKey="material" stroke={C.greenBtn} strokeWidth={2} dot={{ r: 3, fill: C.greenBtn }} name="Material" />
                <Line type="monotone" dataKey="labour" stroke="#f39c12" strokeWidth={2} dot={{ r: 3, fill: "#f39c12" }} name="Labour" />
              </LineChart>
            </ResponsiveContainer>
          </div>
          <div style={{ display: "flex", gap: 16, padding: "10px 20px 16px" }}>
            {[["#1a6b3c", "Material"], ["#f39c12", "Labour"]].map(([col, lbl]) => (
              <div key={lbl} style={{ display: "flex", alignItems: "center", gap: 6 }}>
                <div style={{ width: 24, height: 3, background: col, borderRadius: 2 }} />
                <span style={{ fontSize: 11, color: C.text3 }}>{lbl}</span>
              </div>
            ))}
          </div>
        </Card>

        <Card>
          <SectionBar title="Cost Breakdown" />
          <div style={{ display: "flex", justifyContent: "center", padding: "16px 20px 4px" }}>
            <PieChart width={170} height={170}>
              <Pie data={costBreakdown} cx={85} cy={85} innerRadius={54} outerRadius={80} dataKey="value" stroke="none">
                {costBreakdown.map((e, i) => <Cell key={i} fill={e.color} />)}
              </Pie>
              <text x={85} y={80} textAnchor="middle" dominantBaseline="central" style={{ fontSize: 20, fontWeight: 800, fill: C.text1 }}>68%</text>
              <text x={85} y={100} textAnchor="middle" dominantBaseline="central" style={{ fontSize: 9, fill: C.text3 }}>AVG DONE</text>
            </PieChart>
          </div>
          <div style={{ padding: "0 20px 16px", display: "flex", flexDirection: "column", gap: 8 }}>
            {costBreakdown.map(({ name, value, color }) => (
              <div key={name} style={{ display: "flex", alignItems: "center", gap: 8 }}>
                <div style={{ width: 10, height: 10, borderRadius: "50%", background: color, flexShrink: 0 }} />
                <span style={{ fontSize: 12, color: C.text2, flex: 1 }}>{name}</span>
                <div style={{ width: 60, height: 4, borderRadius: 2, background: color, opacity: 0.7 }} />
                <span style={{ fontSize: 12, fontWeight: 700, color: C.text1, minWidth: 32, textAlign: "right" }}>{value}%</span>
              </div>
            ))}
          </div>
        </Card>
      </div>

      <div style={{ display: "grid", gridTemplateColumns: "1fr 310px", gap: 16 }}>
        <Card>
          <SectionBar title="Active Projects" action="View all →" />
          <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
            <thead><tr><Th>Project</Th><Th>Status</Th><Th>Budget</Th><Th>Progress</Th><Th>Due</Th></tr></thead>
            <tbody>
              {projects.slice(0, 4).map((p) => (
                <tr key={p.id}>
                  <Td><div style={{ fontWeight: 700, color: C.text1, fontSize: 12 }}>{p.name}</div><div style={{ fontSize: 10, color: C.text3 }}>{p.id}</div></Td>
                  <Td>{statusBadge(p.status)}</Td>
                  <Td style={{ fontWeight: 700, color: C.text1 }}>KES {p.budget}</Td>
                  <Td>
                    <div style={{ display: "flex", alignItems: "center", gap: 7 }}>
                      <div style={{ width: 75, height: 5, background: "#eee", borderRadius: 3, overflow: "hidden", border: `1px solid ${C.border}` }}>
                        <div style={{ width: `${p.progress}%`, height: "100%", borderRadius: 3, background: progressColor(p.progress) }} />
                      </div>
                      <span style={{ fontSize: 10, color: C.text3 }}>{p.progress}%</span>
                    </div>
                  </Td>
                  <Td style={{ color: C.text3, fontSize: 11 }}>{p.due}</Td>
                </tr>
              ))}
            </tbody>
          </table>
        </Card>

        <Card>
          <SectionBar title="Labour - This Week" />
          <div style={{ padding: "14px 20px 0", height: 170 }}>
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={labourWeek} margin={{ top: 0, right: 0, left: -24, bottom: 0 }} barSize={14}>
                <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.04)" />
                <XAxis dataKey="day" tick={{ fontSize: 10, fill: C.text3 }} axisLine={false} tickLine={false} />
                <YAxis tick={{ fontSize: 10, fill: C.text3 }} axisLine={false} tickLine={false} />
                <Tooltip contentStyle={{ fontSize: 11, borderRadius: 8, border: `1px solid ${C.border}` }} />
                <Bar dataKey="skilled" stackId="a" fill={C.greenBtn} radius={[0, 0, 0, 0]} name="Skilled" />
                <Bar dataKey="unskilled" stackId="a" fill="#a8d5b5" radius={[4, 4, 0, 0]} name="Unskilled" />
              </BarChart>
            </ResponsiveContainer>
          </div>
          <div style={{ padding: "14px 20px 16px", display: "grid", gridTemplateColumns: "1fr 1fr", gap: 10 }}>
            <div style={{ background: C.greenCard, borderRadius: 10, padding: "12px" }}>
              <div style={{ fontSize: 10, color: C.text3, fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.07em", marginBottom: 4 }}>Workers</div>
              <div style={{ fontSize: 22, fontWeight: 800, color: C.greenDark }}>143</div>
            </div>
            <div style={{ background: "#fff8e1", borderRadius: 10, padding: "12px" }}>
              <div style={{ fontSize: 10, color: C.text3, fontWeight: 700, textTransform: "uppercase", letterSpacing: "0.07em", marginBottom: 4 }}>Daily Cost</div>
              <div style={{ fontSize: 22, fontWeight: 800, color: "#e65100" }}>8.4K</div>
            </div>
          </div>
        </Card>
      </div>
    </div>
  );
}

function ProjectsView() {
  const [filter, setFilter] = useState("All");
  const statuses = ["All", "Active", "Delayed", "Planning", "On Hold", "Completed"];
  const filtered = filter === "All" ? projects : projects.filter((p) => p.status === filter);
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between" }}>
        <span style={{ fontSize: 12, color: C.text3, fontWeight: 600 }}>Showing {filtered.length} projects</span>
        <div style={{ display: "flex", gap: 6 }}>
          {statuses.map((s) => (
            <button key={s} onClick={() => setFilter(s)} style={{ padding: "5px 12px", borderRadius: 20, fontSize: 11, fontWeight: 600, cursor: "pointer", background: filter === s ? C.greenBtn : C.white, color: filter === s ? "#fff" : C.text2, border: `1px solid ${filter === s ? C.greenBtn : C.border}` }}>{s}</button>
          ))}
        </div>
      </div>
      <Card>
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>Project</Th><Th>Type</Th><Th>Status</Th><Th>Budget</Th><Th>Progress</Th><Th>Manager</Th><Th>Due</Th></tr></thead>
          <tbody>
            {filtered.map((p) => (
              <tr key={p.id}>
                <Td><div style={{ fontWeight: 700, color: C.text1, fontSize: 12 }}>{p.name}</div><div style={{ fontSize: 10, color: C.text3 }}>{p.id}</div></Td>
                <Td style={{ color: C.text3 }}>{p.type}</Td>
                <Td>{statusBadge(p.status)}</Td>
                <Td style={{ fontWeight: 700, color: C.text1 }}>KES {p.budget}</Td>
                <Td><div style={{ display: "flex", alignItems: "center", gap: 7 }}><div style={{ width: 75, height: 5, background: "#eee", borderRadius: 3, overflow: "hidden" }}><div style={{ width: `${p.progress}%`, height: "100%", borderRadius: 3, background: progressColor(p.progress) }} /></div><span style={{ fontSize: 10, color: C.text3 }}>{p.progress}%</span></div></Td>
                <Td style={{ color: C.text2 }}>{p.manager}</Td>
                <Td style={{ color: C.text3, fontSize: 11 }}>{p.due}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function BOQView({ onNewBoq }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Bills of Quantities" action="+ New BOQ" onAction={onNewBoq} />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>BOQ Ref</Th><Th>Project</Th><Th>Items</Th><Th>Total (KES)</Th><Th>Status</Th></tr></thead>
          <tbody>
            {boqData.map((r) => (
              <tr key={r.ref}>
                <Td><span style={{ fontWeight: 700, color: C.text1 }}>{r.ref}</span></Td>
                <Td style={{ color: C.text2 }}>{r.project}</Td>
                <Td style={{ color: C.text2 }}>{r.items}</Td>
                <Td style={{ fontWeight: 700, color: C.text1 }}>{r.total}</Td>
                <Td>{statusBadge(r.status)}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function BOMView({ onNewBom }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Bills of Materials" action="+ New BOM" onAction={onNewBom} />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>BOM Ref</Th><Th>Project</Th><Th>Material</Th><Th>Qty</Th><Th>Unit (KES)</Th><Th>Total (KES)</Th></tr></thead>
          <tbody>
            {bomData.map((r) => (
              <tr key={r.ref}>
                <Td><span style={{ fontWeight: 700, color: C.text1 }}>{r.ref}</span></Td>
                <Td style={{ color: C.text2 }}>{r.project}</Td>
                <Td style={{ color: C.text2 }}>{r.material}</Td>
                <Td style={{ color: C.text2 }}>{r.qty.toLocaleString()}</Td>
                <Td style={{ color: C.text2 }}>{r.unit.toLocaleString()}</Td>
                <Td style={{ fontWeight: 700, color: C.text1 }}>{r.total}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function RequisitionsView() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Material Requisitions" action="+ New Requisition" />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>Requisition No</Th><Th>Material</Th><Th>Qty</Th><Th>Section</Th><Th>Status</Th><Th>Requested By</Th></tr></thead>
          <tbody>
            {requisitionsData.map((r) => (
              <tr key={r.no}>
                <Td><span style={{ fontWeight: 700, color: C.text1 }}>{r.no}</span></Td>
                <Td style={{ color: C.text2 }}>{r.material}</Td>
                <Td style={{ color: C.text2 }}>{r.qty}</Td>
                <Td style={{ color: C.text2 }}>{r.section}</Td>
                <Td>{statusBadge(r.status)}</Td>
                <Td style={{ color: C.text2 }}>{r.requestedBy}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function MaterialsDeliveredView() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Materials Delivered" />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>Material</Th><Th>Requisitioned</Th><Th>Received</Th><Th>Variance</Th><Th>UoM</Th><Th>Unit Price</Th><Th>Supplier</Th><Th>Date</Th></tr></thead>
          <tbody>
            {deliveredData.map((r) => (
              <tr key={`${r.material}-${r.date}`}>
                <Td style={{ color: C.text2 }}>{r.material}</Td>
                <Td style={{ color: C.text2 }}>{r.requisitioned}</Td>
                <Td style={{ color: C.text2 }}>{r.received}</Td>
                <Td style={{ color: r.variance < 0 ? "#c0392b" : C.text2, fontWeight: 700 }}>{r.variance}</Td>
                <Td style={{ color: C.text2 }}>{r.uom}</Td>
                <Td style={{ color: C.text2 }}>KES {r.unitPrice.toLocaleString()}</Td>
                <Td style={{ color: C.text2 }}>{r.supplier}</Td>
                <Td style={{ color: C.text3 }}>{r.date}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function InventoryManagementView() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Inventory Management" />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>Material</Th><Th>UoM</Th><Th>Total Quantity in Stock</Th><Th>Issue Stock</Th></tr></thead>
          <tbody>
            {inventoryData.map((r) => (
              <tr key={r.material}>
                <Td style={{ color: C.text2 }}>{r.material}</Td>
                <Td style={{ color: C.text2 }}>{r.uom}</Td>
                <Td style={{ fontWeight: 700, color: C.text1 }}>{r.stock}</Td>
                <Td><button style={{ border: `1px solid ${C.border2}`, background: C.white, borderRadius: 6, padding: "4px 10px", cursor: "pointer" }}>Issue</button></Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function StockUsageView() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Stock Usage History" />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>Date</Th><Th>Material</Th><Th>Quantity Used</Th><Th>Section</Th></tr></thead>
          <tbody>
            {stockUsageData.map((r) => (
              <tr key={`${r.date}-${r.material}-${r.section}`}>
                <Td style={{ color: C.text3 }}>{r.date}</Td>
                <Td style={{ color: C.text2 }}>{r.material}</Td>
                <Td style={{ fontWeight: 700, color: C.text1 }}>{r.qty}</Td>
                <Td style={{ color: C.text2 }}>{r.section}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function LabourView() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 18 }}>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4,minmax(0,1fr))", gap: 14 }}>
        <StatCard icon="👷" val="143" label="Total Workers" sub="Active on-site" />
        <StatCard icon="🔧" val="67" label="Skilled" sub="47% of workforce" />
        <StatCard icon="💵" val="KES 8.4K" label="Daily Cost" sub="This week avg" />
        <StatCard icon="✅" val="94%" label="Attendance" sub="+2% vs last week" />
      </div>
      <Card>
        <SectionBar title="Labour - This Week" />
        <div style={{ padding: "16px 20px 0", height: 200 }}>
          <ResponsiveContainer width="100%" height="100%">
            <BarChart data={labourWeek} margin={{ top: 0, right: 0, left: -20, bottom: 0 }} barSize={18}>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(0,0,0,0.04)" />
              <XAxis dataKey="day" tick={{ fontSize: 11, fill: C.text3 }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fontSize: 11, fill: C.text3 }} axisLine={false} tickLine={false} />
              <Tooltip contentStyle={{ fontSize: 11, borderRadius: 8 }} />
              <Legend wrapperStyle={{ fontSize: 11, paddingTop: 8 }} />
              <Bar dataKey="skilled" stackId="a" fill={C.greenBtn} name="Skilled" radius={[0, 0, 0, 0]} />
              <Bar dataKey="unskilled" stackId="a" fill="#a8d5b5" name="Unskilled" radius={[4, 4, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </div>
        <div style={{ padding: "16px 20px" }}>
          <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
            <thead><tr><Th>Project</Th><Th>Workers</Th><Th>Skilled</Th><Th>Daily Rate (KES)</Th><Th>Attendance</Th></tr></thead>
            <tbody>
              {labourProjects.map((r) => (
                <tr key={r.name}>
                  <Td><span style={{ fontWeight: 700, color: C.text1 }}>{r.name}</span></Td>
                  <Td style={{ fontWeight: 700, color: C.text1 }}>{r.workers}</Td>
                  <Td style={{ color: C.text2 }}>{r.skilled}</Td>
                  <Td style={{ color: C.text2 }}>{r.rate}</Td>
                  <Td>{statusBadge(r.attOk ? "Active" : "Delayed")}</Td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>
    </div>
  );
}

function CostView() {
  const cats = [
    { name: "Materials", spent: 820, total: 1400, color: C.greenBtn },
    { name: "Labour", spent: 640, total: 1100, color: "#3498db" },
    { name: "Equipment", spent: 210, total: 900, color: "#f39c12" },
    { name: "Subcontractors", spent: 90, total: 800, color: "#9b59b6" },
  ];
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 18 }}>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4,minmax(0,1fr))", gap: 14 }}>
        <StatCard icon="💰" val="KES 4.2M" label="Total Committed" sub="All projects" />
        <StatCard icon="📉" val="KES 1.76M" label="Total Spent" sub="42% utilised" />
        <StatCard icon="🏦" val="KES 2.44M" label="Remaining" sub="58% of budget" />
        <StatCard icon="⚠️" val="2" label="Overruns" sub="Projects over budget" variant="pink" />
      </div>
      <Card>
        <SectionBar title="Cost Breakdown by Category" />
        <div style={{ padding: "20px 24px", display: "flex", flexDirection: "column", gap: 18 }}>
          {cats.map(({ name, spent, total, color }) => {
            const pct = Math.round((spent / total) * 100);
            return (
              <div key={name}>
                <div style={{ display: "flex", justifyContent: "space-between", marginBottom: 8 }}>
                  <span style={{ fontSize: 13, fontWeight: 700, color: C.text1 }}>{name}</span>
                  <span style={{ fontSize: 12, fontWeight: 700, color: C.text1 }}>KES {spent}K <span style={{ color: C.text3, fontWeight: 400 }}>/ {total}K</span></span>
                </div>
                <div style={{ height: 10, background: "#eee", borderRadius: 5, overflow: "hidden" }}>
                  <div style={{ width: `${pct}%`, height: "100%", background: color, borderRadius: 5 }} />
                </div>
              </div>
            );
          })}
        </div>
      </Card>
    </div>
  );
}

function ReportingView() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
      <Card>
        <SectionBar title="Reports" action="+ Generate Report" />
        <table style={{ width: "100%", borderCollapse: "collapse", fontSize: 11.5 }}>
          <thead><tr><Th>Report Name</Th><Th>Type</Th><Th>Period</Th><Th>Generated</Th><Th>Status</Th></tr></thead>
          <tbody>
            {reports.map((r) => (
              <tr key={r.name}>
                <Td><span style={{ fontWeight: 700, color: C.text1 }}>{r.name}</span></Td>
                <Td style={{ color: C.text2 }}>{r.type}</Td>
                <Td style={{ color: C.text2 }}>{r.period}</Td>
                <Td style={{ color: C.text3 }}>{r.gen}</Td>
                <Td>{statusBadge(r.status)}</Td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}

function validateCreateProjectForm(form) {
  if (!form.project_uid.trim()) return "Enter a Project ID.";
  if (!/^[a-zA-Z0-9_-]+$/.test(form.project_uid.trim())) {
    return "Project ID may only contain letters, numbers, dashes and underscores.";
  }
  if (!form.name.trim()) return "Enter a project name.";
  if (!form.description.trim()) return "Enter a description.";
  const w = parseInt(form.project_duration, 10);
  if (!Number.isFinite(w) || w < 1) return "Enter a duration of at least 1 week.";
  if (form.budget === "" || form.budget === null || Number.isNaN(Number(form.budget))) return "Enter a budget.";
  if (!form.type) return "Select a project type.";
  if (!form.address.trim()) return "Enter a project address.";
  return null;
}

function Modal({ onClose }) {
  const [step, setStep] = useState(1);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState(null);
  const [form, setForm] = useState({
    project_uid: "",
    name: "",
    description: "",
    project_duration: "",
    budget: "",
    type: "",
    priority: "Medium",
    address: "",
  });

  const inputStyle = { width: "100%", padding: "9px 13px", border: `1px solid ${C.border2}`, borderRadius: 8, fontSize: 12, outline: "none", fontFamily: "inherit", color: C.text1 };
  const labelStyle = { fontSize: 10, fontWeight: 700, color: C.text3, textTransform: "uppercase", letterSpacing: "0.09em" };
  const setField = (key, value) => setForm((f) => ({ ...f, [key]: value }));

  const handleNext = () => {
    setError(null);
    const msg = validateCreateProjectForm(form);
    if (msg) {
      setError(msg);
      return;
    }
    setStep(2);
  };

  const handleCreate = async () => {
    setError(null);
    const msg = validateCreateProjectForm(form);
    if (msg) {
      setError(msg);
      setStep(1);
      return;
    }
    setSubmitting(true);
    try {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
      const res = await fetch("/dashboard/projects", {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": csrf || "",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          project_uid: form.project_uid.trim(),
          name: form.name.trim(),
          description: form.description.trim(),
          project_duration: parseInt(form.project_duration, 10),
          budget: String(form.budget),
          address: form.address.trim(),
          project_type: form.type,
          priority: form.priority,
        }),
      });
      const payload = await res.json().catch(() => ({}));
      if (!res.ok) {
        if (payload.errors) {
          const first = Object.values(payload.errors).flat()[0];
          throw new Error(first || payload.message || "Validation failed.");
        }
        throw new Error(payload.message || `Could not create project (${res.status}).`);
      }
      window.location.reload();
    } catch (e) {
      setError(e.message || "Something went wrong.");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div style={{ position: "absolute", inset: 0, background: "rgba(0,0,0,0.3)", display: "flex", alignItems: "center", justifyContent: "center", zIndex: 99, minHeight: "100%" }}>
      <div style={{ background: C.white, borderRadius: 16, width: 520, border: `1px solid ${C.border}`, maxHeight: "92%", overflowY: "auto" }}>
        <div style={{ padding: "22px 26px 0", display: "flex", alignItems: "flex-start", justifyContent: "space-between" }}>
          <div>
            <div style={{ fontSize: 18, fontWeight: 800, color: C.text1, letterSpacing: "-0.02em" }}>Create Project</div>
            <div style={{ fontSize: 11, color: C.text3, marginTop: 3 }}>
              {step === 1 ? "Step 1 of 2 — Project details" : "Step 2 of 2 — Confirm"}
            </div>
          </div>
          <button type="button" onClick={onClose} style={{ background: "none", border: `1px solid ${C.border2}`, borderRadius: 8, width: 30, height: 30, cursor: "pointer", fontSize: 14, color: C.text2, display: "flex", alignItems: "center", justifyContent: "center" }}>✕</button>
        </div>
        <div style={{ display: "flex", alignItems: "center", padding: "20px 26px" }}>
          <div style={{ display: "flex", flexDirection: "column", alignItems: "center", gap: 5 }}>
            <div
              style={{
                width: 32,
                height: 32,
                borderRadius: "50%",
                background: step === 1 ? C.greenBtn : C.greenBtn,
                border: `2px solid ${C.greenBtn}`,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
              }}
            >
              {step === 1 ? (
                <span style={{ color: "#fff", fontSize: 12, fontWeight: 800 }}>1</span>
              ) : (
                <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M3.5 8l3.5 3.5 5.5-6" stroke="white" strokeWidth="2.5" strokeLinecap="round" /></svg>
              )}
            </div>
            <div style={{ fontSize: 10, fontWeight: 700, color: C.greenBtn }}>Create Project</div>
          </div>
          <div style={{ flex: 1, height: 1, background: C.border, margin: "0 8px", marginBottom: 17 }} />
          <div style={{ display: "flex", flexDirection: "column", alignItems: "center", gap: 5 }}>
            <div
              style={{
                width: 32,
                height: 32,
                borderRadius: "50%",
                background: step === 2 ? C.greenBtn : C.white,
                border: `2px solid ${step === 2 ? C.greenBtn : C.border2}`,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                fontSize: 12,
                fontWeight: 700,
                color: step === 2 ? "#fff" : C.text3,
              }}
            >
              {step === 2 ? "✓" : "2"}
            </div>
            <div style={{ fontSize: 10, fontWeight: 700, color: step === 2 ? C.greenBtn : C.text3 }}>Confirm</div>
          </div>
        </div>
        {error && (
          <div style={{ padding: "0 26px", marginBottom: 8, fontSize: 12, color: "#c0392b", fontWeight: 600 }}>
            {error}
          </div>
        )}
        {step === 1 ? (
          <div style={{ padding: "0 26px 26px", display: "flex", flexDirection: "column", gap: 14 }}>
            <div style={{ fontSize: 13, fontWeight: 700, color: C.text1, borderBottom: `1px solid ${C.border}`, paddingBottom: 10 }}>Project Details</div>
            <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
              <label style={labelStyle}>Project ID</label>
              <input type="text" value={form.project_uid} onChange={(e) => setField("project_uid", e.target.value)} placeholder="e.g. PRJ-013 (letters, numbers, dashes)" style={inputStyle} />
            </div>
            <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
              <label style={labelStyle}>Project Name</label>
              <input type="text" value={form.name} onChange={(e) => setField("name", e.target.value)} placeholder="Enter project name" style={inputStyle} />
            </div>
            <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
              <label style={labelStyle}>Description</label>
              <textarea value={form.description} onChange={(e) => setField("description", e.target.value)} placeholder="Brief description of scope and objectives..." style={{ ...inputStyle, minHeight: 75, resize: "vertical" }} />
            </div>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 12 }}>
              <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
                <label style={labelStyle}>Duration (weeks)</label>
                <input type="number" min={1} value={form.project_duration} onChange={(e) => setField("project_duration", e.target.value)} placeholder="e.g. 24" style={inputStyle} />
              </div>
              <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
                <label style={labelStyle}>Budget (KES)</label>
                <input type="number" min={0} value={form.budget} onChange={(e) => setField("budget", e.target.value)} placeholder="e.g. 500000" style={inputStyle} />
              </div>
            </div>
            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 12 }}>
              <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
                <label style={labelStyle}>Type</label>
                <select value={form.type} onChange={(e) => setField("type", e.target.value)} style={{ ...inputStyle, background: C.white }}>
                  <option value="">Select type...</option>
                  {["Residential", "Commercial", "Infrastructure", "Industrial"].map((o) => (
                    <option key={o} value={o}>{o}</option>
                  ))}
                </select>
              </div>
              <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
                <label style={labelStyle}>Priority</label>
                <select value={form.priority} onChange={(e) => setField("priority", e.target.value)} style={{ ...inputStyle, background: C.white }}>
                  {["Medium", "High", "Critical", "Low"].map((o) => (
                    <option key={o} value={o}>{o}</option>
                  ))}
                </select>
              </div>
            </div>
            <div style={{ display: "flex", flexDirection: "column", gap: 6 }}>
              <label style={labelStyle}>Project Address</label>
              <input type="text" value={form.address} onChange={(e) => setField("address", e.target.value)} placeholder="Enter site address" style={inputStyle} />
            </div>
            <div style={{ display: "flex", justifyContent: "flex-end", gap: 10, paddingTop: 4 }}>
              <SecBtn onClick={onClose}>Cancel</SecBtn>
              <PriBtn onClick={handleNext}>Next: Confirm →</PriBtn>
            </div>
          </div>
        ) : (
          <div style={{ padding: "0 26px 26px", display: "flex", flexDirection: "column", gap: 14 }}>
            <div style={{ fontSize: 13, fontWeight: 700, color: C.text1, borderBottom: `1px solid ${C.border}`, paddingBottom: 10 }}>Review &amp; create</div>
            <div style={{ fontSize: 12, color: C.text2, lineHeight: 1.6 }}>
              <div><strong style={{ color: C.text1 }}>Project ID:</strong> {form.project_uid}</div>
              <div><strong style={{ color: C.text1 }}>Name:</strong> {form.name}</div>
              <div><strong style={{ color: C.text1 }}>Description:</strong> {form.description}</div>
              <div><strong style={{ color: C.text1 }}>Duration:</strong> {form.project_duration} weeks</div>
              <div><strong style={{ color: C.text1 }}>Budget (KES):</strong> {form.budget}</div>
              <div><strong style={{ color: C.text1 }}>Type:</strong> {form.type}</div>
              <div><strong style={{ color: C.text1 }}>Priority:</strong> {form.priority}</div>
              <div><strong style={{ color: C.text1 }}>Address:</strong> {form.address}</div>
            </div>
            <div style={{ display: "flex", justifyContent: "flex-end", gap: 10, paddingTop: 4 }}>
              <SecBtn disabled={submitting} onClick={() => { setStep(1); setError(null); }}>← Back</SecBtn>
              <PriBtn disabled={submitting} onClick={handleCreate}>{submitting ? "Creating…" : "Create project"}</PriBtn>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

const NAV = [
  {
    section: "Apps",
    items: [
      { id: "dashboard", href: "/dashboard", label: "Dashboard", icon: <svg viewBox="0 0 16 16" fill="currentColor" width={16} height={16}><rect x="1" y="1" width="6" height="6" rx="1.5" /><rect x="9" y="1" width="6" height="6" rx="1.5" /><rect x="1" y="9" width="6" height="6" rx="1.5" /><rect x="9" y="9" width="6" height="6" rx="1.5" /></svg> },
      { id: "boq", href: "/boq", label: "Bills of Quantities", icon: <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" width={16} height={16}><path d="M3 2h10a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1z" /><path d="M5 5h6M5 8h6M5 11h4" /></svg> },
      { id: "bom", href: "/boms", label: "Bills of Materials", icon: <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" width={16} height={16}><path d="M3 2h10a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V3a1 1 0 011-1z" /><path d="M5 5h6M5 8h4" /></svg> },
      {
        id: "material",
        href: "/materials",
        label: "Manage Material",
        icon: <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" width={16} height={16}><path d="M8 2L2 5v6l6 3 6-3V5L8 2z" /><path d="M8 2v9M2 5l6 3 6-3" /></svg>,
        sub: [
          { label: "Requisitions", href: "/requisitions" },
          { label: "Materials Delivered", href: "/materials/delivered" },
          { label: "Inventory Management", href: "/materials/inventory" },
          { label: "Stock Usage", href: "/materials/usage" },
        ],
      },
    ],
  },
  { section: "Workforce", items: [{ id: "labour", href: "/workers", label: "Labour", icon: <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" width={16} height={16}><circle cx="8" cy="5" r="3" /><path d="M2 14c0-3.314 2.686-5 6-5s6 1.686 6 5" /></svg> }] },
  { section: "Finance", items: [{ id: "cost", href: "/cost-tracking", label: "Cost Tracking", icon: <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" width={16} height={16}><path d="M2 12l4-4 3 3 5-6" /></svg> }, { id: "reporting", href: "/reports", label: "Reporting", icon: <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" strokeWidth="1.5" width={16} height={16}><rect x="2" y="2" width="12" height="12" rx="1.5" /><path d="M5 9v3M8 6v6M11 8v4" /></svg> }] },
];

function JengaMetrics() {
  const [matOpen, setMatOpen] = useState(false);
  const [modal, setModal] = useState(false);
  const [dashboardSummary, setDashboardSummary] = useState(null);
  const [apiToken, setApiToken] = useState(null);
  const getActiveNavId = (path) => {
    if (path.startsWith("/boq") || path.startsWith("/bq_documents")) return "boq";
    if (path.startsWith("/boms")) return "bom";
    if (path.startsWith("/requisitions")) return "requisitions";
    if (path.startsWith("/materials/delivered")) return "materialsDelivered";
    if (path.startsWith("/materials/inventory")) return "inventoryManagement";
    if (path.startsWith("/materials/usage")) return "stockUsage";
    if (path.startsWith("/materials")) return "material";
    if (path.startsWith("/workers") || path.startsWith("/attendance") || path.startsWith("/payments")) return "labour";
    if (path.startsWith("/cost-tracking")) return "cost";
    if (path.startsWith("/reports")) return "reporting";
    return "dashboard";
  };
  const pathname = typeof window !== "undefined" ? window.location.pathname : "/dashboard";
  const activeNavId = getActiveNavId(pathname);
  const materialOpenByPath = activeNavId === "material";
  const dashboardShellPaths = ["/dashboard", "/ui-preview"];
  const isDashboardShellPath = dashboardShellPaths.some((path) => pathname.startsWith(path));

  const fetchDashboardApiToken = async () => {
    const response = await fetch("/dashboard/api-token", {
      method: "GET",
      credentials: "include",
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    });
    if (!response.ok) return null;
    const payload = await response.json();
    return payload?.data?.token || null;
  };

  const handleCreateBoq = async () => {
    if (typeof window === "undefined") return;

    const title = window.prompt("Enter BOQ title");
    if (!title || !title.trim()) return;

    const token = apiToken || (await fetchDashboardApiToken());
    if (!token) {
      window.alert("Unable to authenticate API request. Please refresh and try again.");
      return;
    }

    const response = await fetch("/api/v1/boq/documents", {
      method: "POST",
      credentials: "include",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify({
        title: title.trim(),
        description: "Created from dashboard shell",
        units: 1,
      }),
    });

    const payload = await response.json().catch(() => null);
    if (!response.ok || !payload?.data?.id) {
      window.alert("Failed to create BOQ.");
      return;
    }

    window.location.assign(`/bq_documents/${payload.data.id}`);
  };

  const handleLogout = async () => {
    if (typeof window === "undefined") return;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/logout";
    form.style.display = "none";

    const tokenInput = document.createElement("input");
    tokenInput.type = "hidden";
    tokenInput.name = "_token";
    tokenInput.value = csrf || "";
    form.appendChild(tokenInput);

    document.body.appendChild(form);
    form.submit();
  };

  useEffect(() => {
    if (!isDashboardShellPath) return;

    let mounted = true;
    fetch("/dashboard/api-token", {
      method: "GET",
      credentials: "include",
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    })
      .then((res) => (res.ok ? res.json() : null))
      .then((tokenJson) => {
        const token = tokenJson?.data?.token;
        if (!token) return null;
        if (mounted) setApiToken(token);
        return fetch("/api/v1/dashboard/summary", {
          method: "GET",
          credentials: "include",
          headers: { Accept: "application/json", Authorization: `Bearer ${token}` },
        });
      })
      .then((res) => (res && res.ok ? res.json() : null))
      .then((json) => {
        if (!mounted || !json?.data) return;
        setDashboardSummary(json.data);
      })
      .catch(() => {
        // Keep static fallback if API is unavailable.
      });

    return () => {
      mounted = false;
    };
  }, [isDashboardShellPath]);

  return (
    <div style={{ fontFamily: "'Segoe UI',system-ui,sans-serif", fontSize: 13, background: C.page, height: "100vh", display: "flex", flexDirection: "column", position: "relative" }}>
      <div style={{ display: "flex", flex: 1, overflow: "hidden" }}>
        <div style={{ width: 232, background: C.white, borderRight: `1px solid ${C.border}`, display: "flex", flexDirection: "column", flexShrink: 0, overflow: "hidden" }}>
          <div style={{ display: "flex", alignItems: "center", gap: 10, padding: "18px 18px 16px", borderBottom: `1px solid ${C.border}` }}>
            <div style={{ fontSize: 26, lineHeight: 1 }}>🏗️</div>
            <span style={{ fontSize: 16, fontWeight: 700, color: C.greenDark, letterSpacing: "-0.02em" }}>Jenga<span style={{ color: "#3da85e" }}>Metrics</span></span>
          </div>
          <div style={{ flex: 1, overflowY: "auto", padding: "8px 10px" }}>
            {NAV.map(({ section, items }) => (
              <div key={section}>
                <div style={{ fontSize: 9, fontWeight: 700, color: C.text3, letterSpacing: "0.12em", textTransform: "uppercase", padding: "14px 8px 6px" }}>{section}</div>
                {items.map((item) => (
                  <div key={item.id}>
                    {item.sub ? (
                      <div
                        style={{
                          display: "flex",
                          alignItems: "center",
                          gap: 6,
                          width: "100%",
                        }}
                      >
                        <a
                          href={item.href}
                          style={{
                            display: "flex",
                            alignItems: "center",
                            gap: 10,
                            padding: "9px 10px",
                            borderRadius: 8,
                            cursor: "pointer",
                            fontSize: 12.5,
                            fontWeight: 500,
                            border: "none",
                            textAlign: "left",
                            transition: "background 0.12s",
                            background: activeNavId === item.id ? C.greenCard : "none",
                            color: C.greenDark,
                            justifyContent: "flex-start",
                            textDecoration: "none",
                            flex: 1,
                          }}
                        >
                          <span
                            style={{
                              display: "flex",
                              alignItems: "center",
                              gap: 10,
                              color: activeNavId === item.id ? C.greenDark : C.greenMid,
                              opacity: activeNavId === item.id ? 1 : 0.8,
                            }}
                          >
                            {item.icon}
                            <span
                              style={{
                                color: activeNavId === item.id ? C.greenDark : C.text2,
                                fontWeight: activeNavId === item.id ? 700 : 500,
                              }}
                            >
                              {item.label}
                            </span>
                          </span>
                        </a>
                        <button
                          type="button"
                          onClick={() => setMatOpen((o) => !o)}
                          aria-label="Toggle material submenu"
                          style={{
                            width: 28,
                            height: 28,
                            borderRadius: 8,
                            border: `1px solid ${C.border}`,
                            background: C.white,
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                            cursor: "pointer",
                            flexShrink: 0,
                          }}
                        >
                          <svg
                            width="12"
                            height="12"
                            viewBox="0 0 16 16"
                            fill="none"
                            stroke={C.text3}
                            strokeWidth="2"
                            style={{
                              transform: (matOpen || materialOpenByPath) ? "rotate(180deg)" : "none",
                              transition: "transform 0.2s",
                            }}
                          >
                            <path d="M5 7l3 3 3-3" />
                          </svg>
                        </button>
                      </div>
                    ) : (
                      <a
                        href={item.href}
                        style={{
                          display: "flex",
                          alignItems: "center",
                          gap: 10,
                          padding: "9px 10px",
                          borderRadius: 8,
                          cursor: "pointer",
                          fontSize: 12.5,
                          fontWeight: 500,
                          width: "100%",
                          border: "none",
                          textAlign: "left",
                          transition: "background 0.12s",
                          background: activeNavId === item.id ? C.greenCard : "none",
                          color: C.greenDark,
                          justifyContent: "flex-start",
                          textDecoration: "none",
                        }}
                      >
                        <span
                          style={{
                            display: "flex",
                            alignItems: "center",
                            gap: 10,
                            color: activeNavId === item.id ? C.greenDark : C.greenMid,
                            opacity: activeNavId === item.id ? 1 : 0.8,
                          }}
                        >
                          {item.icon}
                          <span
                            style={{
                              color: activeNavId === item.id ? C.greenDark : C.text2,
                              fontWeight: activeNavId === item.id ? 700 : 500,
                            }}
                          >
                            {item.label}
                          </span>
                        </span>
                      </a>
                    )}
                    {item.sub && (matOpen || materialOpenByPath) && (
                      <div style={{ paddingLeft: 36 }}>
                        {item.sub.map((s) => (
                          <a
                            key={s.label}
                            href={s.href}
                            style={{
                              display: "block",
                              padding: "7px 10px",
                              fontSize: 12,
                              color: pathname.startsWith(s.href) ? C.greenDark : C.text2,
                              fontWeight: pathname.startsWith(s.href) ? 700 : 500,
                              borderRadius: 6,
                              width: "100%",
                              border: "none",
                              background: pathname.startsWith(s.href) ? C.greenCard : "none",
                              textAlign: "left",
                              cursor: "pointer",
                              textDecoration: "none",
                            }}
                          >
                            {s.label}
                          </a>
                        ))}
                      </div>
                    )}
                  </div>
                ))}
              </div>
            ))}
          </div>
          <a href="/account" style={{ padding: "14px 16px", borderTop: `1px solid ${C.border}`, display: "flex", alignItems: "center", gap: 10, textDecoration: "none" }}>
            <div style={{ width: 32, height: 32, borderRadius: "50%", background: C.greenCard, display: "flex", alignItems: "center", justifyContent: "center", fontSize: 11, fontWeight: 700, color: C.greenDark, flexShrink: 0 }}>AM</div>
            <div><div style={{ fontSize: 12, fontWeight: 600, color: C.text1 }}>Alex Mutua</div><div style={{ fontSize: 10, color: C.text3 }}>Project Manager</div></div>
          </a>
        </div>

        <div style={{ flex: 1, display: "flex", flexDirection: "column", minWidth: 0, overflow: "hidden" }}>
          <div style={{ display: "flex", alignItems: "center", gap: 10, padding: "0 24px", height: 52, background: C.white, borderBottom: `1px solid ${C.border}`, flexShrink: 0 }}>
            <span style={{ fontSize: 16, fontWeight: 700, color: C.text1, letterSpacing: "-0.02em" }}>Dashboard</span>
            <span style={{ fontSize: 11, color: C.text3, background: C.page, border: `1px solid ${C.border}`, padding: "3px 10px", borderRadius: 20, fontWeight: 500 }}>March 2026</span>
            <div style={{ flex: 1 }} />
            <div style={{ width: 34, height: 34, borderRadius: 8, background: C.white, border: `1px solid ${C.border}`, display: "flex", alignItems: "center", justifyContent: "center", cursor: "pointer", position: "relative" }}>
              <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke={C.text2} strokeWidth="1.5"><path d="M8 2a4 4 0 014 4v2l1 2H3l1-2V6a4 4 0 014-4zM6 12a2 2 0 004 0" /></svg>
              <div style={{ width: 8, height: 8, background: "#e74c3c", borderRadius: "50%", position: "absolute", top: 5, right: 5, border: `2px solid ${C.white}` }} />
            </div>
            <SecBtn>Project Steps</SecBtn>
            <SecBtn onClick={handleCreateBoq}>+ New BOQ</SecBtn>
            <PriBtn onClick={() => setModal(true)}>+ New Project</PriBtn>
            <SecBtn onClick={handleLogout}>Logout</SecBtn>
          </div>

          <div style={{ flex: 1, overflowY: "auto", padding: "20px 24px", background: C.page }}>
            {isDashboardShellPath ? (
              <DashboardView onNewProject={() => setModal(true)} summary={dashboardSummary} />
            ) : (
              <div style={{ background: C.white, border: `1px solid ${C.border}`, borderRadius: 12, padding: 20 }}>
                <div style={{ fontSize: 14, fontWeight: 700, color: C.text1, marginBottom: 8 }}>
                  Open this module from the server page
                </div>
                <div style={{ fontSize: 12.5, color: C.text2, lineHeight: 1.5 }}>
                  This React shell is now dashboard-only. Use the sidebar links to open module pages such as BoQ, BoM, Materials, Labour, Cost Tracking, and Reporting.
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
      {modal && <Modal onClose={() => setModal(false)} />}
    </div>
  );
}

const el = document.getElementById("jenga-metrics-root");
if (el) {
  createRoot(el).render(<JengaMetrics />);
}
