@extends('layouts.main')

@section('title', 'Pohon Keputusan C4.5')

@push('styles')
<style>
/* Tree Container - Auto Height */
.tree-container {
    width: 100%;
    height: 700px;
    overflow: hidden;
    background: #ffffff;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    position: relative;
    cursor: grab;
}

/* Full Screen Mode */
.card.maximized-card .tree-container {
    height: calc(100vh - 200px);
}

.tree-container:active {
    cursor: grabbing;
}

/* Zoom Controls - Lebih Besar untuk Export Button */
.zoom-controls {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 100;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    padding: 5px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.zoom-controls button {
    width: 40px;
    height: 40px;
    border: none;
    background: #667eea;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.2s;
}

.zoom-controls button:hover {
    background: #5568d3;
    transform: scale(1.1);
}

.zoom-controls button:active {
    transform: scale(0.95);
}

.zoom-controls button.btn-export {
    background: #28a745;
    width: 40px;
}

.zoom-controls button.btn-export:hover {
    background: #218838;
}

.zoom-controls .divider {
    height: 1px;
    background: #dee2e6;
    margin: 3px 0;
}

/* Info Badge */
.info-badge {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background: rgba(255,255,255,0.95);
    padding: 8px 15px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    font-size: 12px;
    z-index: 100;
}

/* Loading Overlay */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.9);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    border-radius: 8px;
}

.loading-overlay.show {
    display: flex;
}

.loading-spinner {
    text-align: center;
}

.loading-spinner i {
    font-size: 3em;
    color: #667eea;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Rules Table */
.table-rules {
    font-size: 0.9em;
}

.table-rules th {
    background: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 10;
    border-bottom: 2px solid #dee2e6;
}

.condition-text {
    font-family: 'Courier New', monospace;
    font-size: 0.85em;
    background: #f8f9fa;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
    margin: 2px 0;
}
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pohon Keputusan C4.5</h1>
                <small class="text-muted">Visualisasi model decision tree</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tiket.training.index') }}">Training</a></li>
                    <li class="breadcrumb-item active">Decision Tree</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Back Button --}}
        <div class="mb-3">
            <a href="{{ route('tiket.training.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Training
            </a>
        </div>

        {{-- Tree Visualization --}}
        <div class="card card-primary card-outline" id="treeCard">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sitemap"></i> Visualisasi Pohon Keputusan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize" onclick="handleResize()">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="alert alert-info m-3 mb-2">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Interaksi:</strong> 
                    <span class="badge badge-secondary"><i class="fas fa-mouse"></i> Drag</span> untuk menggeser | 
                    <span class="badge badge-secondary"><i class="fas fa-search-plus"></i> Scroll</span> untuk zoom | 
                    <span class="badge badge-success"><i class="fas fa-download"></i> Export</span> untuk download PNG | 
                    <span class="badge badge-primary">Ungu</span> = Keputusan | 
                    <span class="badge badge-success">Hijau</span> = Low | 
                    <span class="badge badge-info">Biru</span> = Medium | 
                    <span class="badge badge-warning">Kuning</span> = High | 
                    <span class="badge badge-danger">Merah</span> = Critical
                </div>
                
                <div id="tree-svg-container" class="tree-container">
                    {{-- Zoom Controls with Export --}}
                    <div class="zoom-controls">
                        <button onclick="zoomIn()" title="Zoom In">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button onclick="zoomOut()" title="Zoom Out">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button onclick="resetZoom()" title="Reset">
                            <i class="fas fa-sync"></i>
                        </button>
                        <div class="divider"></div>
                        <button onclick="exportTreeImage()" class="btn-export" title="Export ke PNG">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    
                    {{-- Loading Overlay --}}
                    <div id="loadingOverlay" class="loading-overlay">
                        <div class="loading-spinner">
                            <i class="fas fa-circle-notch"></i>
                            <p class="mt-3 mb-0"><strong>Generating image...</strong></p>
                        </div>
                    </div>
                    
                    {{-- Info Badge --}}
                    <div class="info-badge">
                        <i class="fas fa-hand-paper text-primary"></i> 
                        <strong>Drag</strong> untuk menggeser | 
                        <strong>Scroll</strong> untuk zoom
                    </div>
                </div>
            </div>
        </div>

        {{-- Rules dalam Tabel --}}
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-ol"></i> Pola & Rules
                </h3>
                <div class="card-tools">
                    <span class="badge badge-info mr-2">{{ count($rules) }} rules</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if(count($rules) > 0)
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-hover table-striped table-rules mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 80px;" class="text-center">Rule #</th>
                                    <th>Kondisi (IF)</th>
                                    <th style="width: 180px;" class="text-center">Hasil (THEN)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rules as $index => $rule)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge badge-primary" style="font-size: 0.95em; padding: 6px 12px;">
                                                #{{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            @foreach($rule['conditions'] as $condIndex => $condition)
                                                @if($condIndex > 0)
                                                    <strong class="text-primary mx-2">AND</strong> 
                                                @endif
                                                <span class="condition-text">
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $condition['attribute'])) }}</strong> 
                                                    = <em>"{{ $condition['value'] }}"</em>
                                                </span>
                                                @if($condIndex < count($rule['conditions']) - 1)
                                                    <br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $conclusionClass = [
                                                    'Low' => 'badge-success',
                                                    'Medium' => 'badge-info',
                                                    'High' => 'badge-warning',
                                                    'Critical' => 'badge-danger'
                                                ][$rule['conclusion']] ?? 'badge-secondary';
                                            @endphp
                                            <span class="badge {{ $conclusionClass }}" style="font-size: 1.1em; padding: 10px 20px;">
                                                <i class="fas fa-flag"></i> {{ $rule['conclusion'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4">
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Belum ada rules. Silakan train model terlebih dahulu.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tree JSON (Developer) --}}
        <div class="card card-secondary collapsed-card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-code"></i> Tree JSON (Developer)</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <pre style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;">de>{{ json_encode($tree, JSON_PRETTY_PRINT) }}</code></pre>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
// Global variables
let svgElement, zoomBehavior, mainGroup;

const treeData = @json($tree);

console.log('Tree Data:', treeData);

if (!treeData || Object.keys(treeData).length === 0) {
    document.getElementById('tree-svg-container').innerHTML = 
        '<div class="alert alert-warning text-center m-4">' +
        '<i class="fas fa-exclamation-triangle fa-3x mb-3"></i>' +
        '<h5>Pohon Keputusan Kosong</h5>' +
        '<p>Model belum di-train. Silakan upload data dan train model terlebih dahulu.</p>' +
        '</div>';
} else {
    try {
        renderFlowchartTree(treeData);
    } catch (error) {
        console.error('Error rendering tree:', error);
        document.getElementById('tree-svg-container').innerHTML = 
            '<div class="alert alert-danger text-center m-4">' +
            '<i class="fas fa-times-circle fa-3x mb-3"></i>' +
            '<h5>Gagal Menampilkan Pohon</h5>' +
            '<p>' + error.message + '</p>' +
            '</div>';
    }
}

function renderFlowchartTree(treeData) {
    // Canvas SUPER BESAR untuk spacing 7x
    const width = 6000;
    const height = 4000;
    const nodeWidth = 120;
    const nodeHeight = 45;

    const container = d3.select("#tree-svg-container");
    
    // Clear existing SVG only
    container.selectAll('svg').remove();

    // SVG full container size
    svgElement = container.append("svg")
        .attr("width", "100%")
        .attr("height", "100%")
        .attr("id", "tree-svg");

    mainGroup = svgElement.append("g");

    // Zoom behavior
    zoomBehavior = d3.zoom()
        .scaleExtent([0.05, 3])
        .on("zoom", (event) => {
            mainGroup.attr("transform", event.transform);
        });

    svgElement.call(zoomBehavior);

    // Convert to hierarchy
    function convertToHierarchy(node, depth = 0) {
        if (!node) return null;

        const newNode = {
            name: node.type === 'leaf' 
                ? `Urgency: ${node.class || 'Unknown'}` 
                : (node.attribute || 'Root'),
            type: node.type || 'leaf',
            class: node.class,
            depth: depth
        };

        if (node.type === 'node' && node.branches) {
            newNode.children = [];
            Object.keys(node.branches).forEach(key => {
                const child = convertToHierarchy(node.branches[key], depth + 1);
                if (child) {
                    child.branchName = key;
                    newNode.children.push(child);
                }
            });
        }

        return newNode;
    }

    const root = d3.hierarchy(convertToHierarchy(treeData));

    // Tree layout dengan SPACING 7X
    const treeLayout = d3.tree()
        .size([width - 600, height - 600])
        .separation((a, b) => {
            return (a.parent == b.parent ? 12 : 12.5);
        });

    treeLayout(root);

    // Draw links
    mainGroup.selectAll('.link')
        .data(root.links())
        .enter()
        .append('path')
        .attr('class', 'link')
        .attr('d', d => {
            const sourceX = d.source.x;
            const sourceY = d.source.y + nodeHeight/2;
            const targetX = d.target.x;
            const targetY = d.target.y - nodeHeight/2;
            const midY = (sourceY + targetY) / 2;
            
            return `M ${sourceX} ${sourceY}
                    L ${sourceX} ${midY}
                    L ${targetX} ${midY}
                    L ${targetX} ${targetY}`;
        })
        .attr('fill', 'none')
        .attr('stroke', '#6c757d')
        .attr('stroke-width', 2.5)
        .attr('stroke-linecap', 'round');

    // Branch labels
    mainGroup.selectAll('.branch-label')
        .data(root.links())
        .enter()
        .append('text')
        .attr('class', 'branch-label')
        .attr('x', d => d.source.x + 20)
        .attr('y', d => d.source.y + nodeHeight/2 + 30)
        .attr('font-size', '12px')
        .attr('font-weight', '700')
        .attr('fill', '#495057')
        .attr('text-anchor', 'start')
        .text(d => d.target.data.branchName || '');

    // Define gradients
    const defs = mainGroup.append('defs');
    
    const gradients = [
        { id: 'gradient-decision', colors: ['#667eea', '#764ba2'] },
        { id: 'gradient-critical', colors: ['#f8d7da', '#f5c6cb'] },
        { id: 'gradient-high', colors: ['#fff3cd', '#ffeaa7'] },
        { id: 'gradient-medium', colors: ['#d1ecf1', '#bee5eb'] },
        { id: 'gradient-low', colors: ['#d4edda', '#c3e6cb'] }
    ];

    gradients.forEach(grad => {
        const gradient = defs.append('linearGradient')
            .attr('id', grad.id)
            .attr('x1', '0%').attr('y1', '0%')
            .attr('x2', '100%').attr('y2', '100%');
        gradient.append('stop').attr('offset', '0%').attr('stop-color', grad.colors[0]);
        gradient.append('stop').attr('offset', '100%').attr('stop-color', grad.colors[1]);
    });

    // Draw nodes
    const nodes = mainGroup.selectAll('.node')
        .data(root.descendants())
        .enter()
        .append('g')
        .attr('class', 'node')
        .attr('transform', d => `translate(${d.x - nodeWidth/2}, ${d.y - nodeHeight/2})`);

    nodes.append('rect')
        .attr('width', nodeWidth)
        .attr('height', nodeHeight)
        .attr('rx', 8)
        .attr('ry', 8)
        .attr('fill', d => {
            if (d.data.type === 'leaf') {
                const urgency = (d.data.class || '').toLowerCase();
                if (urgency.includes('critical')) return 'url(#gradient-critical)';
                if (urgency.includes('high')) return 'url(#gradient-high)';
                if (urgency.includes('medium')) return 'url(#gradient-medium)';
                return 'url(#gradient-low)';
            }
            return 'url(#gradient-decision)';
        })
        .attr('stroke', d => {
            if (d.data.type === 'leaf') {
                const urgency = (d.data.class || '').toLowerCase();
                if (urgency.includes('critical')) return '#dc3545';
                if (urgency.includes('high')) return '#ffc107';
                if (urgency.includes('medium')) return '#17a2b8';
                return '#28a745';
            }
            return '#5568d3';
        })
        .attr('stroke-width', 3)
        .style('cursor', 'pointer')
        .on('mouseover', function() {
            d3.select(this).attr('filter', 'drop-shadow(0px 6px 12px rgba(0,0,0,0.4))');
        })
        .on('mouseout', function() {
            d3.select(this).attr('filter', 'none');
        });

    nodes.append('text')
        .attr('x', nodeWidth / 2)
        .attr('y', nodeHeight / 2)
        .attr('text-anchor', 'middle')
        .attr('dominant-baseline', 'middle')
        .attr('fill', d => d.data.type === 'leaf' ? '#212529' : 'white')
        .attr('font-weight', '700')
        .attr('font-size', '12px')
        .style('pointer-events', 'none')
        .text(d => d.data.name)
        .call(wrap, nodeWidth - 15);

    // Wrap text
    function wrap(text, width) {
        text.each(function() {
            const text = d3.select(this);
            const words = text.text().split(/\s+/).reverse();
            let word, line = [], lineNumber = 0;
            const lineHeight = 1.1, y = text.attr('y'), dy = 0;
            let tspan = text.text(null).append('tspan').attr('x', nodeWidth/2).attr('y', y).attr('dy', dy + 'em');
            
            while (word = words.pop()) {
                line.push(word);
                tspan.text(line.join(' '));
                if (tspan.node().getComputedTextLength() > width) {
                    line.pop();
                    tspan.text(line.join(' '));
                    line = [word];
                    tspan = text.append('tspan')
                        .attr('x', nodeWidth/2).attr('y', y)
                        .attr('dy', ++lineNumber * lineHeight + dy + 'em').text(word);
                }
            }
        });
    }

    // Center tree awal
    const initialTransform = d3.zoomIdentity.translate(200, 150).scale(0.4);
    svgElement.call(zoomBehavior.transform, initialTransform);
}

// Zoom functions
function zoomIn() {
    svgElement.transition().duration(300).call(zoomBehavior.scaleBy, 1.3);
}

function zoomOut() {
    svgElement.transition().duration(300).call(zoomBehavior.scaleBy, 0.7);
}

function resetZoom() {
    const initialTransform = d3.zoomIdentity.translate(200, 150).scale(0.4);
    svgElement.transition().duration(750).call(zoomBehavior.transform, initialTransform);
}

// Export Tree to PNG Image
// Export Tree to PNG Image - Full A4 Landscape
function exportTreeImage() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    loadingOverlay.classList.add('show');
    
    setTimeout(() => {
        try {
            // Get bounding box of entire tree
            const bbox = mainGroup.node().getBBox();
            
            // A4 Landscape dimensions (300 DPI untuk high quality)
            // A4 = 297mm x 210mm = 3508px x 2480px at 300 DPI
            const dpi = 300;
            const a4WidthMM = 297;
            const a4HeightMM = 210;
            const canvasWidth = Math.round((a4WidthMM / 25.4) * dpi);  // 3508px
            const canvasHeight = Math.round((a4HeightMM / 25.4) * dpi); // 2480px
            
            // Hitung scale agar tree pas di A4 dengan padding
            const padding = 100;
            const scaleX = (canvasWidth - padding * 2) / bbox.width;
            const scaleY = (canvasHeight - padding * 2) / bbox.height;
            const scale = Math.min(scaleX, scaleY, 1); // Max scale 1 (tidak diperbesar)
            
            // Hitung posisi center
            const scaledWidth = bbox.width * scale;
            const scaledHeight = bbox.height * scale;
            const offsetX = (canvasWidth - scaledWidth) / 2 - bbox.x * scale;
            const offsetY = (canvasHeight - scaledHeight) / 2 - bbox.y * scale;
            
            // Clone SVG untuk export
            const svgClone = document.getElementById('tree-svg').cloneNode(true);
            
            // Set ukuran SVG clone
            svgClone.setAttribute('width', canvasWidth);
            svgClone.setAttribute('height', canvasHeight);
            svgClone.setAttribute('viewBox', `0 0 ${canvasWidth} ${canvasHeight}`);
            
            // Set transform untuk center dan scale
            const gElement = svgClone.querySelector('g');
            gElement.setAttribute('transform', `translate(${offsetX}, ${offsetY}) scale(${scale})`);
            
            // Serialize SVG
            const svgData = new XMLSerializer().serializeToString(svgClone);
            
            // Create canvas
            const canvas = document.createElement('canvas');
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;
            const ctx = canvas.getContext('2d');
            
            // White background
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvasWidth, canvasHeight);
            
            // Add title/header
            ctx.fillStyle = '#333';
            ctx.font = 'bold 48px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('Decision Tree C4.5 - Helpdesk System', canvasWidth / 2, 60);
            
            // Add date
            ctx.font = '32px Arial';
            ctx.fillStyle = '#666';
            const dateStr = new Date().toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric' 
            });
            ctx.fillText(dateStr, canvasWidth / 2, 100);
            
            // Create image from SVG
            const img = new Image();
            const blob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            
            img.onload = function() {
                // Draw tree (dengan offset untuk header)
                ctx.save();
                ctx.translate(0, 120); // Space untuk header
                ctx.drawImage(img, 0, 0, canvasWidth, canvasHeight - 120);
                ctx.restore();
                
                URL.revokeObjectURL(url);
                
                // Download PNG
                canvas.toBlob(function(blob) {
                    const link = document.createElement('a');
                    const timestamp = new Date().toISOString().slice(0,10);
                    link.download = `decision-tree-${timestamp}.png`;
                    link.href = URL.createObjectURL(blob);
                    link.click();
                    
                    loadingOverlay.classList.remove('show');
                    
                    // Success notification dengan SweetAlert atau alert biasa
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Berhasil!',
                            text: 'Pohon keputusan telah diexport ke PNG (A4 Landscape, 300 DPI)',
                            timer: 3000
                        });
                    } else {
                        alert('✅ Pohon keputusan berhasil diexport!\nFormat: A4 Landscape (3508x2480px, 300 DPI)');
                    }
                }, 'image/png', 1.0); // Quality 1.0 = maximum
            };
            
            img.onerror = function() {
                URL.revokeObjectURL(url);
                loadingOverlay.classList.remove('show');
                alert('❌ Gagal export image. Silakan coba lagi.');
            };
            
            img.src = url;
            
        } catch (error) {
            console.error('Export error:', error);
            loadingOverlay.classList.remove('show');
            alert('❌ Error: ' + error.message);
        }
    }, 500);
}


// Handle resize saat maximize/minimize
function handleResize() {
    setTimeout(() => {
        if (svgElement) {
            const container = document.getElementById('tree-svg-container');
            const width = container.clientWidth;
            const height = container.clientHeight;
            console.log('Resized:', width, height);
        }
    }, 300);
}

// Listen to window resize
window.addEventListener('resize', handleResize);
</script>
@endpush
